<?php

namespace App\Services;

use App\Models\Estate;
use App\Models\Plot;

class PlotMapService
{
    /**
     * Build the full JSON payload for the interactive estate map.
     * Returns estate info + all plots with normalized coordinates
     * and is_mine flag based on the provided user's active allocations.
     */
    public function buildMapData(Estate $estate, ?int $userId = null): array
    {
        $estate->loadMissing('plots.activeAllocation.user');

        // Get this user's active plot IDs for is_mine flag
        $myPlotIds = [];
        if ($userId) {
            $myPlotIds = $estate->plots
                ->filter(fn($p) => $p->activeAllocation && $p->activeAllocation->user_id == $userId)
                ->pluck('id')
                ->toArray();
        }

        $plots = $estate->plots->map(function (Plot $plot) use ($myPlotIds, $userId) {
            $coordinates = $this->parseCoordinates($plot->coordinates);

            return [
                'id'          => $plot->id,
                'plot_number' => $plot->plot_number,
                'plot_reference' => $plot->plot_reference,
                'size_sqm'    => $plot->size,
                'status'      => $plot->status,
                'coordinates' => $coordinates, // normalized 0.0-1.0 array
                'is_mine'     => in_array($plot->id, $myPlotIds),
                'has_allocation' => $plot->activeAllocation !== null,
                'allocation'  => $plot->activeAllocation ? [
                    'id'                  => $plot->activeAllocation->id,
                    'allocation_reference'=> $plot->activeAllocation->allocation_reference,
                    'allocated_at'        => optional($plot->activeAllocation->allocated_at)->format('d M Y'),
                    'allocation_date'     => optional($plot->activeAllocation->allocation_date)->format('d M Y'),
                ] : null,
            ];
        });

        return [
            'estate' => [
                'id'           => $estate->id,
                'name'         => $estate->name,
                'location'     => $estate->location,
                'layout_file'  => $estate->layout_file ? asset('storage/' . $estate->layout_file) : null,
                'layout_type'  => $estate->layout_type,
                'total_plots'  => $estate->plots->count(),
            ],
            'plots'  => $plots->values()->toArray(),
            'my_plot_ids' => $myPlotIds,
        ];
    }

    /**
     * Parse coordinates from DB (supports both pixel and normalized formats).
     * Always returns an array of {'x': float, 'y': float} normalized 0.0-1.0.
     */
    public function parseCoordinates($raw): array
    {
        if (empty($raw)) {
            return [];
        }

        $coords = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);

        if (empty($coords)) {
            return [];
        }

        // Detect old pixel format [[x,y],...] vs object format [{x,y},...]
        $first = $coords[0] ?? null;
        if (is_array($first) && array_is_list($first)) {
            // Old format: [[100,100], [250,100]] — treat as already normalized if values <= 1.0
            return array_map(fn($p) => ['x' => (float)($p[0] ?? 0), 'y' => (float)($p[1] ?? 0)], $coords);
        }

        return array_map(fn($p) => ['x' => (float)($p['x'] ?? 0), 'y' => (float)($p['y'] ?? 0)], $coords);
    }

    /**
     * Convert normalized coordinates to SVG point string for a given viewBox size.
     * e.g. normalized [{x:0.25, y:0.3}] + width=800, height=500 = "200,150"
     */
    public function toSvgPoints(array $normalizedCoords, float $width, float $height): string
    {
        return collect($normalizedCoords)
            ->map(fn($p) => round($p['x'] * $width, 2) . ',' . round($p['y'] * $height, 2))
            ->implode(' ');
    }

    /**
     * Compute the centroid of a polygon for label placement.
     */
    public function centroid(array $normalizedCoords): array
    {
        if (empty($normalizedCoords)) {
            return ['x' => 0.5, 'y' => 0.5];
        }
        $x = collect($normalizedCoords)->avg('x');
        $y = collect($normalizedCoords)->avg('y');
        return ['x' => $x, 'y' => $y];
    }
}

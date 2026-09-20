<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use App\Models\Plot;
use App\Services\PlotMapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstateMapController extends Controller
{
    public function __construct(private PlotMapService $mapService) {}

    /**
     * List all estates this customer has active plot allocations in.
     */
    public function index()
    {
        $estates = Auth::user()->estatesWithAllocations();
        return view('customer.estates.index', compact('estates'));
    }

    /**
     * Show the interactive estate map for a specific estate.
     * Customers can only access estates where they have an active allocation.
     */
    public function show(Estate $estate)
    {
        // Authorization: customer must have an active allocation in this estate
        $hasAccess = Auth::user()->activeAllocations()
            ->whereHas('plot', fn($q) => $q->where('estate_id', $estate->id))
            ->exists();

        if (!$hasAccess && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have any allocations in this estate.');
        }

        $estate->load('plots');
        $mapData = $this->mapService->buildMapData($estate, Auth::id());

        return view('customer.estates.show', compact('estate', 'mapData'));
    }

    /**
     * JSON API: returns map data for the interactive SVG overlay.
     */
    public function mapData(Estate $estate)
    {
        $hasAccess = Auth::user()->activeAllocations()
            ->whereHas('plot', fn($q) => $q->where('estate_id', $estate->id))
            ->exists();

        if (!$hasAccess && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $estate->load('plots.activeAllocation');
        $data = $this->mapService->buildMapData($estate, Auth::id());

        return response()->json($data);
    }

    /**
     * Show plot detail. Only exposes full info for customer's own plots.
     */
    public function plotDetail(Estate $estate, Plot $plot)
    {
        abort_unless($plot->estate_id === $estate->id, 404);

        $userId = Auth::id();
        $allocation = $plot->activeAllocation;
        $isMine = $allocation && $allocation->user_id === $userId;

        if ($isMine) {
            $allocation->load('documents');
        }

        // Only return safe public info for plots not owned by this user
        $data = [
            'id'           => $plot->id,
            'plot_number'  => $plot->plot_number,
            'size_sqm'     => $plot->size,
            'status'       => $plot->status,
            'estate_name'  => $estate->name,
            'is_mine'      => $isMine,
        ];

        if ($isMine && $allocation) {
            $data['allocation'] = [
                'reference'      => $allocation->allocation_reference,
                'allocated_at'   => optional($allocation->allocated_at)->format('d M Y'),
                'allocation_date'=> optional($allocation->allocation_date)->format('d M Y'),
                'payment_status' => $allocation->payment_status,
            ];
            $data['plot_reference'] = $plot->plot_reference;
        }

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return view('customer.estates.plot-detail', compact('plot', 'estate', 'isMine', 'allocation'));
    }
}

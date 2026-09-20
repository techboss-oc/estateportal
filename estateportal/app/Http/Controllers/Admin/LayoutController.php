<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use App\Models\Plot;
use App\Services\PlotMapService;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function __construct(private PlotMapService $mapService) {}

    public function index()
    {
        $estates = Estate::withCount('plots')->get();
        return view('admin.layouts.index', compact('estates'));
    }

    public function edit(Estate $estate)
    {
        $estate->load('plots');
        return view('admin.layouts.editor', compact('estate'));
    }

    /**
     * JSON endpoint — return map data for the estate editor.
     */
    public function mapData(Estate $estate)
    {
        $estate->load('plots.activeAllocation.user');
        $data = $this->mapService->buildMapData($estate);
        return response()->json($data);
    }

    /**
     * Save/create a NEW plot with its polygon coordinates from the editor.
     */
    public function savePlot(Request $request, Estate $estate)
    {
        $validated = $request->validate([
            'plot_number'    => ['required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('plots')->where(fn($q) => $q->where('estate_id', $estate->id))
            ],
            'size'           => 'required|numeric|min:0',
            'status'         => 'nullable|in:available,reserved,allocated,unavailable',
            'coordinates'    => 'required|array|min:3',
            'coordinates.*.x'=> 'required|numeric|between:0,1',
            'coordinates.*.y'=> 'required|numeric|between:0,1',
            'plot_reference' => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
            'price'          => 'nullable|numeric',
        ]);

        $plot = $estate->plots()->create([
            'plot_number'    => $validated['plot_number'],
            'size'           => $validated['size'],
            'status'         => $validated['status'] ?? 'available',
            'coordinates'    => $validated['coordinates'],
            'plot_reference' => $validated['plot_reference'] ?? null,
            'notes'          => $validated['notes'] ?? null,
            'price'          => $validated['price'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Plot {$plot->plot_number} created and mapped successfully.",
            'plot'    => $plot,
        ]);
    }

    /**
     * Update an existing plot's polygon coordinates and/or metadata.
     */
    public function updatePlot(Request $request, Estate $estate, Plot $plot)
    {
        // Ensure plot belongs to estate
        abort_unless($plot->estate_id === $estate->id, 403);

        $validated = $request->validate([
            'plot_number'    => ['nullable', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('plots')->where(fn($q) => $q->where('estate_id', $estate->id))->ignore($plot->id)
            ],
            'size'           => 'nullable|numeric|min:0',
            'status'         => 'nullable|in:available,reserved,allocated,unavailable',
            'coordinates'    => 'nullable|array|min:3',
            'coordinates.*.x'=> 'required_with:coordinates|numeric|between:0,1',
            'coordinates.*.y'=> 'required_with:coordinates|numeric|between:0,1',
            'plot_reference' => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
            'price'          => 'nullable|numeric',
        ]);

        $plot->update(array_filter($validated, fn($v) => $v !== null));

        return response()->json([
            'success' => true,
            'message' => "Plot {$plot->plot_number} updated.",
            'plot'    => $plot->fresh(),
        ]);
    }

    /**
     * Delete a plot — only if it has no active allocation.
     */
    public function deletePlot(Estate $estate, Plot $plot)
    {
        abort_unless($plot->estate_id === $estate->id, 403);

        if ($plot->activeAllocation()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Allocated plots cannot be deleted. Deactivate or archive the allocation first.",
            ], 422);
        }

        $plotNumber = $plot->plot_number;
        $plot->delete();

        return response()->json([
            'success' => true,
            'message' => "Plot {$plotNumber} deleted successfully.",
        ]);
    }

    /**
     * Bulk-save existing plot coordinates (used by editor "Save Changes" button).
     */
    public function update(Request $request, Estate $estate)
    {
        $request->validate([
            'plots'              => 'required|array',
            'plots.*.id'         => 'required|exists:plots,id',
            'plots.*.coordinates'=> 'required|array|min:3',
        ]);

        foreach ($request->plots as $plotData) {
            $estate->plots()->where('id', $plotData['id'])->update([
                'coordinates' => $plotData['coordinates'],
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Layout mapping saved successfully.']);
    }

    public function uploadBlueprint(Request $request, Estate $estate)
    {
        $request->validate([
            'blueprint' => 'required|mimes:jpeg,png,jpg,svg,pdf|max:20480',
        ]);

        $path = $request->file('blueprint')->store('layouts', 'public');
        $type = $request->file('blueprint')->getClientOriginalExtension();
        $estate->update(['layout_file' => $path, 'layout_type' => strtolower($type)]);

        return back()->with('success', 'Blueprint uploaded successfully.');
    }
}

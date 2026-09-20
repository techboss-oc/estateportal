<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plot;
use App\Models\Estate;
use Illuminate\Http\Request;

class PlotController extends Controller
{
    public function index(Request $request)
    {
        $plots = Plot::with('estate')
            ->when($request->estate_id, function($query) use ($request) {
                return $query->where('estate_id', $request->estate_id);
            })
            ->when($request->status, function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->paginate(15);
            
        $estates = Estate::all();
        
        return view('admin.plots.index', compact('plots', 'estates'));
    }

    public function create()
    {
        $estates = Estate::all();
        return view('admin.plots.create', compact('estates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estate_id' => 'required|exists:estates,id',
            'plot_number' => 'required|string|max:255',
            'size' => 'required|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,reserved,allocated',
            'coordinates' => 'nullable|json',
        ]);

        Plot::create($validated);

        return redirect()->route('admin.plots.index')
            ->with('success', 'Plot created successfully.');
    }

    public function edit(Plot $plot)
    {
        $estates = Estate::all();
        return view('admin.plots.edit', compact('plot', 'estates'));
    }

    public function update(Request $request, Plot $plot)
    {
        $validated = $request->validate([
            'estate_id' => 'required|exists:estates,id',
            'plot_number' => 'required|string|max:255',
            'size' => 'required|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,reserved,allocated',
            'coordinates' => 'nullable|json',
        ]);

        $plot->update($validated);

        return redirect()->route('admin.plots.index')
            ->with('success', 'Plot updated successfully.');
    }

    public function destroy(Plot $plot)
    {
        $plot->delete();

        return redirect()->route('admin.plots.index')
            ->with('success', 'Plot deleted successfully.');
    }
}

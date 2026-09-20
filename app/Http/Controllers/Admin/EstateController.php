<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use Illuminate\Http\Request;

class EstateController extends Controller
{
    public function index()
    {
        $estates = Estate::withCount('plots')->paginate(10);
        return view('admin.estates.index', compact('estates'));
    }

    public function create()
    {
        return view('admin.estates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_area' => 'nullable|numeric',
        ]);

        Estate::create($validated);

        return redirect()->route('admin.estates.index')
            ->with('success', 'Estate created successfully.');
    }

    public function show(Estate $estate)
    {
        return view('admin.estates.show', compact('estate'));
    }

    public function edit(Estate $estate)
    {
        return view('admin.estates.edit', compact('estate'));
    }

    public function update(Request $request, Estate $estate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_area' => 'nullable|numeric',
        ]);

        $estate->update($validated);

        return redirect()->route('admin.estates.index')
            ->with('success', 'Estate updated successfully.');
    }

    public function destroy(Estate $estate)
    {
        $estate->delete();

        return redirect()->route('admin.estates.index')
            ->with('success', 'Estate deleted successfully.');
    }
}

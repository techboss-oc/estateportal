<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlotAllocation;

class PropertyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $allocations = PlotAllocation::with(['plot.estate'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
            
        return view('customer.properties.index', compact('allocations'));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        $allocation = PlotAllocation::with(['plot.estate'])
            ->where('user_id', $user->id)
            ->findOrFail($id);
            
        return view('customer.properties.show', compact('allocation'));
    }
}

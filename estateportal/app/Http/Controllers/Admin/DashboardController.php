<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use App\Models\Plot;
use App\Models\PlotAllocation;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_estates' => Estate::count(),
            'total_plots' => Plot::count(),
            'allocated_plots' => Plot::where('status', 'allocated')->count(),
            'available_plots' => Plot::where('status', 'available')->count(),
            'reserved_plots' => Plot::where('status', 'reserved')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
        ];
        
        $estates = Estate::withCount('plots')->get();
        $recentAllocations = PlotAllocation::with(['customer', 'plot.estate'])->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'estates', 'recentAllocations'));
    }
}

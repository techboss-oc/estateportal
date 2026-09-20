<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlotAllocation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user is customer
        if ($user->role !== 'customer') {
            abort(403, 'Unauthorized access.');
        }

        $allocations = PlotAllocation::with(['plot.estate'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        return view('customer.dashboard', compact('user', 'allocations'));
    }
}

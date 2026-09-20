<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlotAllocation;
use App\Models\Plot;
use App\Models\User;
use App\Services\PlotAllocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlotAllocationController extends Controller
{
    public function __construct(private PlotAllocationService $allocationService) {}

    public function index()
    {
        $allocations = PlotAllocation::with(['customer', 'plot.estate'])
            ->latest()
            ->paginate(15);
        return view('admin.allocations.index', compact('allocations'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('first_name')->get();
        $plots     = Plot::with('estate')->whereIn('status', ['available', 'reserved'])->orderBy('plot_number')->get();
        $estates   = \App\Models\Estate::all();
        return view('admin.allocations.create', compact('customers', 'plots', 'estates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'plot_id'        => 'required|exists:plots,id',
            'amount_paid'    => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:pending,partial,completed',
            'allocation_date'=> 'nullable|date',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $plot     = Plot::with('estate')->findOrFail($validated['plot_id']);
        $customer = User::findOrFail($validated['user_id']);

        try {
            $allocation = $this->allocationService->allocate(
                $plot,
                $customer,
                $validated,
                Auth::user()
            );

            return redirect()->route('admin.allocations.show', $allocation)
                ->with('success', "Plot {$plot->plot_number} successfully allocated to {$customer->full_name}. Reference: {$allocation->allocation_reference}");

        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['plot_id' => $e->getMessage()]);
        }
    }

    public function show(PlotAllocation $allocation)
    {
        $allocation->load('customer', 'plot.estate', 'createdBy');
        return view('admin.allocations.show', compact('allocation'));
    }

    public function edit(PlotAllocation $allocation)
    {
        $allocation->load('customer', 'plot.estate');
        return view('admin.allocations.edit', compact('allocation'));
    }

    public function update(Request $request, PlotAllocation $allocation)
    {
        $validated = $request->validate([
            'amount_paid'    => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,partial,completed',
            'notes'          => 'nullable|string',
        ]);

        $allocation->update($validated);

        return redirect()->route('admin.allocations.show', $allocation)
            ->with('success', 'Allocation updated successfully.');
    }

    public function destroy(PlotAllocation $allocation)
    {
        try {
            $this->allocationService->revoke($allocation, Auth::user());
        } catch (\Throwable $e) {
            return back()->with('error', 'Could not revoke allocation: ' . $e->getMessage());
        }

        return redirect()->route('admin.allocations.index')
            ->with('success', 'Allocation revoked and plot is now available.');
    }
}

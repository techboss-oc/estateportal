<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlotAllocation;

class PaymentController extends Controller
{
    public function create($id)
    {
        $allocation = PlotAllocation::with('plot.estate')->findOrFail($id);
        return view('customer.payments.create', compact('allocation'));
    }

    public function store(Request $request, $id)
    {
        $allocation = PlotAllocation::findOrFail($id);
        
        // Simulated payment functionality for demo
        $allocation->update([
            'payment_status' => 'completed',
            'amount_paid' => $allocation->plot->price
        ]);

        return redirect()->route('customer.properties.show', $allocation->id)
            ->with('success', 'Mock payment processed successfully!');
    }
}

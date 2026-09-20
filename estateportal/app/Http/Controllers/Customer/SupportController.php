<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $subject = $request->query('subject', '');
        return view('customer.support.index', compact('subject'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Simulated support ticket submission
        return redirect()->route('customer.dashboard')
            ->with('success', 'Your support ticket has been submitted successfully! An agent will contact you shortly.');
    }
}

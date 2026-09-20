<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Fetch all system-wide notifications / activity logs
        return view('admin.notifications.index');
    }

    public function create()
    {
        // Create an announcement / manual notification to customers
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        // broadcast push/email notifications
        return redirect()->route('admin.notifications.index')->with('success', 'Notification dispatched');
    }
}

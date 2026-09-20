<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Assume the user has a documents relationship
        // $documents = $user->documents()->latest()->paginate(15);
        return view('customer.documents.index');
    }
}

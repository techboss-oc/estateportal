<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $estates = Estate::with(['plots' => function($query) {
            $query->select('id', 'estate_id', 'plot_number', 'status', 'coordinates', 'size', 'price');
        }])->get();
        
        return view('customer.map.index', compact('estates'));
    }
}

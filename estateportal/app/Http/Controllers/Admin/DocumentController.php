<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        // $documents = Document::with('customer', 'estate')->latest()->paginate(15);
        return view('admin.documents.index');
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        // Handle document storage (S3 or local disk)
        // Document::create([...]);
        
        return redirect()->route('admin.documents.index')->with('success', 'Document uploaded successfully');
    }

    public function destroy($id)
    {
        // Delete document (and file)
        return redirect()->route('admin.documents.index')->with('success', 'Document deleted successfully');
    }
}

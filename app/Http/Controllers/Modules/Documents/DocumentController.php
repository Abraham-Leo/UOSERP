<?php

namespace App\Http\Controllers\Modules\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        return view('modules.documents.index');
    }

    public function create()
    {
        return view('modules.documents.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('documents.index')
            ->with('success', 'Document created.');
    }

    public function show($id)
    {
        return view('modules.documents.show');
    }

    public function edit($id)
    {
        return view('modules.documents.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('documents.index')
            ->with('success', 'Updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('documents.index');
    }

    public function upload(Request $request)
    {
        return back()->with('success', 'File uploaded.');
    }

    public function download($id)
    {
        return back();
    }

    public function approve($id)
    {
        return back()->with('success', 'Approved.');
    }
}
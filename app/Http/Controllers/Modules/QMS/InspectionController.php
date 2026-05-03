<?php

namespace App\Http\Controllers\Modules\QMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index()
    {
        return view('modules.qms.inspections.index');
    }

    public function create()
    {
        return view('modules.qms.inspections.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('qms.inspections.index')
            ->with('success', 'Inspection logged.');
    }

    public function show($id)
    {
        return view('modules.qms.inspections.show');
    }

    public function edit($id)
    {
        return view('modules.qms.inspections.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('qms.inspections.index')
            ->with('success', 'Updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('qms.inspections.index');
    }
}
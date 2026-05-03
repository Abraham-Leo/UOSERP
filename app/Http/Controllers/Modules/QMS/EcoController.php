<?php

namespace App\Http\Controllers\Modules\QMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EcoController extends Controller
{
    public function index()
    {
        return view('modules.qms.eco.index');
    }

    public function create()
    {
        return view('modules.qms.eco.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('qms.eco.index')
            ->with('success', 'ECO created.');
    }

    public function show($id)
    {
        return view('modules.qms.eco.show');
    }

    public function edit($id)
    {
        return view('modules.qms.eco.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('qms.eco.index')
            ->with('success', 'ECO updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('qms.eco.index');
    }

    public function approve($eco)
    {
        return back()->with('success', 'ECO approved.');
    }
}
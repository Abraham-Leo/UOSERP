<?php

namespace App\Http\Controllers\Modules\Purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReceivingController extends Controller
{
    public function index()
    {
        return view('modules.purchasing.receiving.index');
    }

    public function create()
    {
        return view('modules.purchasing.receiving.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('purchasing.receiving.index')
            ->with('success', 'Receipt created.');
    }

    public function show($id)
    {
        return view('modules.purchasing.receiving.show');
    }

    public function edit($id)
    {
        return view('modules.purchasing.receiving.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('purchasing.receiving.index')
            ->with('success', 'Receipt updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('purchasing.receiving.index');
    }

    public function receive($po)
    {
        return back()->with('success', 'Items received.');
    }
}
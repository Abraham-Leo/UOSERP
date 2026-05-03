<?php

namespace App\Http\Controllers\Modules\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RmaController extends Controller
{
    public function index()
    {
        return view('modules.shipping.rma.index', ['rmas' => collect()]);
    }

    public function create()
    {
        return view('modules.shipping.rma.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('shipping.rma.index')
            ->with('success', 'RMA created.');
    }

    public function show($id)
    {
        return view('modules.shipping.rma.show');
    }

    public function edit($id)
    {
        return view('modules.shipping.rma.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('shipping.rma.index')
            ->with('success', 'RMA updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('shipping.rma.index');
    }
}
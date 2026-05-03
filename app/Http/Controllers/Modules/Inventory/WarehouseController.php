<?php

namespace App\Http\Controllers\Modules\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return view('modules.inventory.warehouses.index');
    }

    public function create()
    {
        return view('modules.inventory.warehouses.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Warehouse created.');
    }

    public function show($id)
    {
        return view('modules.inventory.warehouses.show');
    }

    public function edit($id)
    {
        return view('modules.inventory.warehouses.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Warehouse updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('inventory.warehouses.index');
    }
}
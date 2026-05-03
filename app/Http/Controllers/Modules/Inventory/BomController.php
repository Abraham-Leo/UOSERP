<?php

namespace App\Http\Controllers\Modules\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Bom;
use Illuminate\Http\Request;

class BomController extends Controller
{
    public function index()
    {
        return view('modules.inventory.boms.index');
    }

    public function create()
    {
        return view('modules.inventory.boms.form', [
            'bom' => new Bom,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('inventory.boms.index')
            ->with('success', 'BOM created.');
    }

    public function show($id)
    {
        return view('modules.inventory.boms.show');
    }

    public function edit($id)
    {
        return view('modules.inventory.boms.form', [
            'bom' => Bom::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('inventory.boms.index')
            ->with('success', 'BOM updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('inventory.boms.index')
            ->with('success', 'BOM deleted.');
    }
}
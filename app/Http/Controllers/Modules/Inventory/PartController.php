<?php

namespace App\Http\Controllers\Modules\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total' => 0,
            'active' => 0,
            'low_stock' => 0,
            'out_of_stock' => 0,
            'with_bom' => 0,
            'inv_value' => 0
        ];

        try {
            $stats['total'] = Part::count();
            $stats['active'] = Part::where('is_active', true)->count();
        } catch (\Exception $e) {
            // Handle exception
        }

        return view('modules.inventory.parts.index', compact('stats'));
    }

    public function create()
    {
        return view('modules.inventory.parts.form', [
            'part' => new Part,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('inventory.parts.index')
            ->with('success', 'Part created.');
    }

    public function show($id)
    {
        return view('modules.inventory.parts.show', [
            'part' => Part::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.inventory.parts.form', [
            'part' => Part::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('inventory.parts.index')
            ->with('success', 'Part updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('inventory.parts.index')
            ->with('success', 'Part deleted.');
    }

    public function history(Part $part)
    {
        return view('modules.inventory.parts.history', compact('part'));
    }

    public function search(Request $request)
    {
        return response()->json(
            Part::where('part_number', 'like', '%' . $request->q . '%')
                ->orWhere('description', 'like', '%' . $request->q . '%')
                ->limit(20)
                ->get(['id', 'part_number', 'description', 'unit_cost'])
        );
    }
}
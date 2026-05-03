<?php

namespace App\Http\Controllers\Modules\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        return view('modules.purchasing.vendors.index', [
            'vendors' => Vendor::paginate(25)
        ]);
    }

    public function create()
    {
        return view('modules.purchasing.vendors.form', [
            'vendor' => new Vendor,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('purchasing.vendors.index')
            ->with('success', 'Vendor created.');
    }

    public function show($id)
    {
        return view('modules.purchasing.vendors.show', [
            'vendor' => Vendor::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.purchasing.vendors.form', [
            'vendor' => Vendor::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('purchasing.vendors.index')
            ->with('success', 'Vendor updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('purchasing.vendors.index')
            ->with('success', 'Vendor deleted.');
    }

    public function search(Request $request)
    {
        return response()->json(
            Vendor::where('name', 'like', '%' . $request->q . '%')
                ->limit(20)
                ->get(['id', 'name', 'vendor_number'])
        );
    }
}
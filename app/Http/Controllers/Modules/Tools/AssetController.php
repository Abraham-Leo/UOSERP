<?php

namespace App\Http\Controllers\Modules\Tools;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        return view('modules.tools.assets.index', [
            'assets' => Asset::paginate(25)
        ]);
    }

    public function create()
    {
        return view('modules.tools.assets.form', [
            'asset' => new Asset,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('tools.assets.index')
            ->with('success', 'Asset created.');
    }

    public function show($id)
    {
        return view('modules.tools.assets.show', [
            'asset' => Asset::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.tools.assets.form', [
            'asset' => Asset::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tools.assets.index')
            ->with('success', 'Asset updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('tools.assets.index');
    }

    public function checkout(Asset $asset)
    {
        $asset->update(['status' => 'checked_out']);
        return back()->with('success', 'Checked out.');
    }

    public function checkin(Asset $asset)
    {
        $asset->update(['status' => 'available']);
        return back()->with('success', 'Checked in.');
    }

    public function logMaintenance(Asset $asset)
    {
        return back()->with('success', 'Maintenance logged.');
    }
}
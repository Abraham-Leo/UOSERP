<?php

namespace App\Http\Controllers\Modules\Shipping;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        return view('modules.shipping.shipments.index', ['shipments' => collect()]);
    }

    public function create()
    {
        return view('modules.shipping.shipments.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('shipping.shipments.index')
            ->with('success', 'Shipment created.');
    }

    public function show($id)
    {
        return view('modules.shipping.shipments.show');
    }

    public function edit($id)
    {
        return view('modules.shipping.shipments.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('shipping.shipments.index')
            ->with('success', 'Shipment updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('shipping.shipments.index');
    }

    public function label(Shipment $shipment)
    {
        return back();
    }

    public function ship(Shipment $shipment)
    {
        $shipment->update(['status' => 'shipped']);
        return back()->with('success', 'Shipped.');
    }
}
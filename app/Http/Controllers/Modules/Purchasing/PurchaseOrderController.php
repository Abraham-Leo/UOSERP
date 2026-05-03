<?php

namespace App\Http\Controllers\Modules\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('modules.purchasing.purchase-orders.index', ['pos' => collect()]);
    }

    public function create()
    {
        return view('modules.purchasing.purchase-orders.form', [
            'po' => new PurchaseOrder,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('purchasing.purchase-orders.index')
            ->with('success', 'PO created.');
    }

    public function show($id)
    {
        return view('modules.purchasing.purchase-orders.show', [
            'po' => PurchaseOrder::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.purchasing.purchase-orders.form', [
            'po' => PurchaseOrder::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('purchasing.purchase-orders.index')
            ->with('success', 'PO updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('purchasing.purchase-orders.index')
            ->with('success', 'PO deleted.');
    }

    public function pdf($po)
    {
        return back();
    }

    public function send($po)
    {
        return back()->with('success', 'PO sent.');
    }

    public function acknowledge($po)
    {
        return back()->with('success', 'PO acknowledged.');
    }

    public function dashboard()
    {
        return view('modules.purchasing.dashboard');
    }
}
<?php

namespace App\Http\Controllers\Modules\Sales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        return view('modules.sales.orders.index', ['orders' => collect()]);
    }

    public function create()
    {
        return view('modules.sales.orders.form', [
            'order' => new Order,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('sales.orders.index')
            ->with('success', 'Order created.');
    }

    public function show($id)
    {
        return view('modules.sales.orders.show', [
            'order' => Order::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.sales.orders.form', [
            'order' => Order::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('sales.orders.index')
            ->with('success', 'Order updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('sales.orders.index')
            ->with('success', 'Order deleted.');
    }

    public function pdf(Order $order)
    {
        return response()->download('/tmp/order.pdf');
    }

    public function release(Order $order)
    {
        $order->update([
            'released' => true,
            'released_at' => Carbon::now()
        ]);
        return back()->with('success', 'Released.');
    }

    public function dashboard()
    {
        return view('modules.sales.dashboard');
    }
}
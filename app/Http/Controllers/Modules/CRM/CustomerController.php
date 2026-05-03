<?php

namespace App\Http\Controllers\Modules\CRM;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(25);
        return view('modules.crm.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('modules.crm.customers.form', [
            'customer' => new Customer(),
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:50',
        ]);

        Customer::create($validated);
        
        return redirect()->route('crm.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('modules.crm.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('modules.crm.customers.form', [
            'customer' => $customer,
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:50',
        ]);
        
        $customer->update($validated);
        
        return redirect()->route('crm.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        
        return redirect()->route('crm.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function orders($id)
    {
        $customer = Customer::findOrFail($id);
        $orders = $customer->orders()->paginate(20);
        return view('modules.crm.customers.orders', compact('customer', 'orders'));
    }

    public function statement($id)
    {
        $customer = Customer::findOrFail($id);
        $invoices = $customer->invoices()->with('orders')->get();
        return view('modules.crm.customers.statement', compact('customer', 'invoices'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        $customers = Customer::where('name', 'like', "%{$search}%")
            ->orWhere('customer_number', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(20)
            ->get(['id', 'name', 'customer_number', 'email']);
            
        return response()->json($customers);
    }
}
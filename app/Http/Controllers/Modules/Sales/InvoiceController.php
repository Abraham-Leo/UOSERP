<?php

namespace App\Http\Controllers\Modules\Sales;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('modules.sales.invoices.index', ['invoices' => collect()]);
    }

    public function create()
    {
        return view('modules.sales.invoices.form', [
            'invoice' => new Invoice,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice created.');
    }

    public function show($id)
    {
        return view('modules.sales.invoices.show', [
            'invoice' => Invoice::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.sales.invoices.form', [
            'invoice' => Invoice::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice deleted.');
    }

    public function pdf(Invoice $invoice)
    {
        return response()->download('/tmp/invoice.pdf');
    }

    public function send(Invoice $invoice)
    {
        return back()->with('success', 'Invoice sent.');
    }
}
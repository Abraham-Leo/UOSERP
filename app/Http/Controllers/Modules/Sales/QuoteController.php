<?php

namespace App\Http\Controllers\Modules\Sales;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        return view('modules.sales.quotes.index');
    }

    public function create()
    {
        return view('modules.sales.quotes.form', [
            'quote' => new Quote,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('sales.quotes.index')
            ->with('success', 'Quote created.');
    }

    public function show($id)
    {
        return view('modules.sales.quotes.show', [
            'quote' => Quote::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.sales.quotes.form', [
            'quote' => Quote::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('sales.quotes.index')
            ->with('success', 'Quote updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('sales.quotes.index')
            ->with('success', 'Quote deleted.');
    }

    public function convertToOrder(Quote $quote)
    {
        return redirect()->route('sales.orders.index')
            ->with('success', 'Order created.');
    }

    public function pdf(Quote $quote)
    {
        return response()->download('/tmp/quote.pdf');
    }
}
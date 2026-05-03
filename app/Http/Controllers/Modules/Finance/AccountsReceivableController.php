<?php

namespace App\Http\Controllers\Modules\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountsReceivableController extends Controller
{
    public function index()
    {
        return view('modules.finance.ar.index');
    }

    public function create()
    {
        return view('modules.finance.ar.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.accounts-receivable.index')
            ->with('success', 'Created.');
    }

    public function show($id)
    {
        return view('modules.finance.ar.show');
    }

    public function edit($id)
    {
        return view('modules.finance.ar.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.accounts-receivable.index')
            ->with('success', 'Updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.accounts-receivable.index');
    }

    public function collect($ar)
    {
        return back()->with('success', 'Payment collected.');
    }
}
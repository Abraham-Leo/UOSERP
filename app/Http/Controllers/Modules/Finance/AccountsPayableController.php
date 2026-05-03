<?php

namespace App\Http\Controllers\Modules\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountsPayableController extends Controller
{
    public function index()
    {
        return view('modules.finance.ap.index');
    }

    public function create()
    {
        return view('modules.finance.ap.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.accounts-payable.index')
            ->with('success', 'Voucher created.');
    }

    public function show($id)
    {
        return view('modules.finance.ap.show');
    }

    public function edit($id)
    {
        return view('modules.finance.ap.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.accounts-payable.index')
            ->with('success', 'Voucher updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.accounts-payable.index');
    }

    public function pay($voucher)
    {
        return back()->with('success', 'Payment recorded.');
    }
}
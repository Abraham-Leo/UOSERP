<?php

namespace App\Http\Controllers\Modules\Finance;

use App\Http\Controllers\Controller;

class AccountingController extends Controller
{
    public function dashboard()
    {
        return view('modules.finance.dashboard');
    }

    public function generalLedger()
    {
        return view('modules.finance.gl');
    }

    public function bankReconcile()
    {
        return view('modules.finance.bank-reconcile');
    }
}
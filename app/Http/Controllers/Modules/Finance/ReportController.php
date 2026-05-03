<?php

namespace App\Http\Controllers\Modules\Finance;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function profitLoss()
    {
        return view('modules.finance.reports.pl');
    }

    public function balanceSheet()
    {
        return view('modules.finance.reports.bs');
    }

    public function cashFlow()
    {
        return view('modules.finance.reports.cf');
    }

    public function arAging()
    {
        return view('modules.finance.reports.ar-aging');
    }

    public function apAging()
    {
        return view('modules.finance.reports.ap-aging');
    }
}
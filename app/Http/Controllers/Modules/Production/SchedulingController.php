<?php

namespace App\Http\Controllers\Modules\Production;

use App\Http\Controllers\Controller;

class SchedulingController extends Controller
{
    public function index()
    {
        return view('modules.production.scheduling.index');
    }
}
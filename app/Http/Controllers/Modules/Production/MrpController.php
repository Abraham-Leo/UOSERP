<?php

namespace App\Http\Controllers\Modules\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MrpController extends Controller
{
    public function index()
    {
        return view('modules.production.mrp.index');
    }

    public function run(Request $request)
    {
        return back()->with('success', 'MRP run complete.');
    }
}
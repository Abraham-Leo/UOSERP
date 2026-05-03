<?php

namespace App\Http\Controllers\Modules\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return view('modules.inventory.stock.index');
    }

    public function adjust(Request $request)
    {
        return back()->with('success', 'Inventory adjusted.');
    }

    public function transfer(Request $request)
    {
        return back()->with('success', 'Stock transferred.');
    }

    public function cycleCount()
    {
        return view('modules.inventory.cycle-count');
    }

    public function submitCount(Request $request)
    {
        return back()->with('success', 'Count submitted.');
    }

    public function dashboard()
    {
        return view('modules.inventory.dashboard');
    }

    public function getStock(Request $request, $part)
    {
        return response()->json(['qty' => 0]);
    }
}
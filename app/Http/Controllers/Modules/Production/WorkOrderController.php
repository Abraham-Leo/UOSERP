<?php

namespace App\Http\Controllers\Modules\Production;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WorkOrderController extends Controller
{
    public function index()
    {
        return view('modules.production.work-orders.index');
    }

    public function create()
    {
        return view('modules.production.work-orders.form', [
            'wo' => new WorkOrder,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('production.work-orders.index')
            ->with('success', 'Work order created.');
    }

    public function show($id)
    {
        return view('modules.production.work-orders.show', [
            'wo' => WorkOrder::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.production.work-orders.form', [
            'wo' => WorkOrder::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('production.work-orders.index')
            ->with('success', 'Work order updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('production.work-orders.index');
    }

    public function release(WorkOrder $wo)
    {
        $wo->update([
            'released' => true,
            'released_at' => Carbon::now(),
            'status' => 'released'
        ]);
        return back()->with('success', 'Released.');
    }

    public function complete(WorkOrder $wo)
    {
        $wo->update([
            'status' => 'complete',
            'completed_date' => Carbon::now()
        ]);
        return back()->with('success', 'Complete.');
    }

    public function clockIn(WorkOrder $wo)
    {
        return back()->with('success', 'Clocked in.');
    }

    public function clockOut(WorkOrder $wo)
    {
        return back()->with('success', 'Clocked out.');
    }

    public function shopFloor()
    {
        return view('modules.production.shop-floor');
    }

    public function traveler($wo)
    {
        return view('modules.production.traveler');
    }

    public function dashboard()
    {
        return view('modules.production.dashboard');
    }
}
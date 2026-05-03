<?php

namespace App\Http\Controllers\Modules\QMS;

use App\Http\Controllers\Controller;
use App\Models\Ncr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NcrController extends Controller
{
    public function index()
    {
        return view('modules.qms.ncr.index');
    }

    public function create()
    {
        return view('modules.qms.ncr.form', [
            'ncr' => new Ncr,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('qms.ncr.index')
            ->with('success', 'NCR created.');
    }

    public function show($id)
    {
        return view('modules.qms.ncr.show');
    }

    public function edit($id)
    {
        return view('modules.qms.ncr.form', [
            'ncr' => Ncr::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('qms.ncr.index')
            ->with('success', 'NCR updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('qms.ncr.index');
    }

    public function escalate(Ncr $ncr)
    {
        $ncr->update(['status' => 'mrb']);
        return back()->with('success', 'Escalated to MRB.');
    }

    public function close(Ncr $ncr)
    {
        $ncr->update([
            'status' => 'closed',
            'closed_at' => Carbon::now()
        ]);
        return back()->with('success', 'NCR closed.');
    }

    public function dashboard()
    {
        return view('modules.qms.dashboard');
    }
}
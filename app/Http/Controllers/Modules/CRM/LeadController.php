<?php

namespace App\Http\Controllers\Modules\CRM;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        return view('modules.crm.leads.index', [
            'leads' => Lead::paginate(25)
        ]);
    }

    public function create()
    {
        return view('modules.crm.leads.form', [
            'lead' => new Lead,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('crm.leads.index')
            ->with('success', 'Lead created.');
    }

    public function show($id)
    {
        return view('modules.crm.leads.show', [
            'lead' => Lead::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.crm.leads.form', [
            'lead' => Lead::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('crm.leads.index')
            ->with('success', 'Lead updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('crm.leads.index');
    }

    public function pipeline()
    {
        return view('modules.crm.pipeline', ['leads' => collect()]);
    }
}
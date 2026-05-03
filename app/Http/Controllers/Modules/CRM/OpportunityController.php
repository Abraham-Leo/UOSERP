<?php

namespace App\Http\Controllers\Modules\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index()
    {
        return view('modules.crm.opportunities.index');
    }

    public function create()
    {
        return view('modules.crm.opportunities.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('crm.opportunities.index')
            ->with('success', 'Opportunity created.');
    }

    public function show($id)
    {
        return view('modules.crm.opportunities.show');
    }

    public function edit($id)
    {
        return view('modules.crm.opportunities.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('crm.opportunities.index')
            ->with('success', 'Updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('crm.opportunities.index');
    }
}
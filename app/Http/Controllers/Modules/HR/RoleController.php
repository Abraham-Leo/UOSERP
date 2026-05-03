<?php

namespace App\Http\Controllers\Modules\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('modules.hr.roles.index', [
            'roles' => Role::with('permissions')->get()
        ]);
    }

    public function create()
    {
        return view('modules.hr.roles.form');
    }

    public function store(Request $request)
    {
        return redirect()->route('hr.roles.index')
            ->with('success', 'Role created.');
    }

    public function show($id)
    {
        return view('modules.hr.roles.show');
    }

    public function edit($id)
    {
        return view('modules.hr.roles.form');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('hr.roles.index')
            ->with('success', 'Role updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('hr.roles.index');
    }
}
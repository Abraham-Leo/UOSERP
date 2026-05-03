<?php

namespace App\Http\Controllers\Modules\HR;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('modules.hr.users.index', [
            'users' => User::with('roles')->paginate(25)
        ]);
    }

    public function create()
    {
        return view('modules.hr.users.form', [
            'user' => new User,
            'action' => 'create'
        ]);
    }

    public function store(Request $request)
    {
        return redirect()->route('hr.users.index')
            ->with('success', 'User created.');
    }

    public function show($id)
    {
        return view('modules.hr.users.show', [
            'user' => User::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('modules.hr.users.form', [
            'user' => User::findOrFail($id),
            'action' => 'edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('hr.users.index')
            ->with('success', 'User updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('hr.users.index');
    }

    public function assignRole(User $user, Request $request)
    {
        $user->assignRole($request->role);
        return back()->with('success', 'Role assigned.');
    }
}
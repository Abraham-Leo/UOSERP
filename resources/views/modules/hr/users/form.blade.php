@extends('layouts.app')
@section('title', isset($user->id) ? 'Edit User' : 'New User')
@section('breadcrumb')
    <a href="{{ route('hr.users.index') }}" style="color:var(--text-muted);text-decoration:none">Admin / Users</a> /
    <span class="current">{{ isset($user->id) ? $user->name : 'New User' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($user->id) ? route('hr.users.update',$user) : route('hr.users.store') }}">
@csrf
@if(isset($user->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($user->id) ? 'Edit: '.$user->name : 'New User' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.users.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save User</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">User Information</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name',$user->name ?? '') }}" required></div>
                    <div class="col-md-6"><label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email',$user->email ?? '') }}" required></div>
                    <div class="col-md-6"><label class="form-label">Password {{ isset($user->id) ? '(leave blank to keep)' : '*' }}</label>
                        <input type="password" name="password" class="form-control" {{ !isset($user->id) ? 'required' : '' }} autocomplete="new-password"></div>
                    <div class="col-md-6"><label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password"></div>
                    <div class="col-md-4"><label class="form-label">Title / Position</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title',$user->title ?? '') }}" placeholder="Production Manager..."></div>
                    <div class="col-md-4"><label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department',$user->department ?? '') }}" placeholder="Engineering, Sales..."></div>
                    <div class="col-md-4"><label class="form-label">Employee ID</label>
                        <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id',$user->employee_id ?? '') }}"></div>
                    <div class="col-md-4"><label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone ?? '') }}"></div>
                    <div class="col-md-4"><label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile',$user->mobile ?? '') }}"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Role & Permissions</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Role *</label>
                        <select name="role" class="form-select" required>
                            <option value="">— Select Role —</option>
                            @foreach($roles ?? [] as $role)
                            <option value="{{ $role->name }}" {{ old('role',$user->roles->first()?->name ?? '')===$role->name?'selected':'' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:12px">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active',$user->is_active ?? true)?'checked':'' }} style="width:18px;height:18px;accent-color:var(--accent)">
                            <span style="font-size:13.5px;font-weight:500">Active User</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                            <input type="hidden" name="shop_floor_only" value="0">
                            <input type="checkbox" name="shop_floor_only" value="1" {{ old('shop_floor_only',$user->shop_floor_only ?? false)?'checked':'' }} style="width:18px;height:18px;accent-color:var(--accent)">
                            <div>
                                <div style="font-size:13.5px;font-weight:500">Shop Floor Only</div>
                                <div style="font-size:11.5px;color:var(--text-muted)">Limited to production views</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save User</button>
    </div>
</div>
</form>
@endsection

@extends('layouts.app')
@section('title', isset($role->id) ? 'Edit Role' : 'New Role')
@section('breadcrumb')
    <a href="{{ route('hr.roles.index') }}" style="color:var(--text-muted);text-decoration:none">Admin / Roles</a> /
    <span class="current">{{ isset($role->id) ? $role->name : 'New Role' }}</span>
@endsection
@section('content')
@php $role = $role ?? null; @endphp
<form method="POST" action="{{ isset($role->id) ? route('hr.roles.update',$role) : route('hr.roles.store') }}">
@csrf
@if(isset($role->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($role->id) ? 'Edit Role: '.ucfirst($role->name) : 'New Role' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.roles.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Role</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><div class="card-title">Role Information</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Role Name <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $role->name ?? '') }}" required placeholder="e.g. sales, manager...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Describe this role...">{{ old('description', $role->description ?? '') }}</textarea>
                    </div>
                    <div class="col-12" style="margin-top:8px">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-save"></i> Save Role
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

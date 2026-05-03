@extends('layouts.app')
@section('title', isset($lead->id) ? 'Edit Lead' : 'New Lead')
@section('breadcrumb')
    <a href="{{ route('crm.leads.index') }}" style="color:var(--text-muted);text-decoration:none">CRM / Leads</a> /
    <span class="current">{{ isset($lead->id) ? $lead->name : 'New Lead' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($lead->id) ? route('crm.leads.update',$lead) : route('crm.leads.store') }}">
@csrf
@if(isset($lead->id)) @method('PUT') @endif
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-{{ isset($lead->id) ? 'pen' : 'user-plus' }}" style="color:var(--accent);margin-right:10px"></i>
            {{ isset($lead->id) ? 'Edit: '.$lead->name : 'New Lead' }}
        </h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('crm.leads.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Lead</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--accent)"></i> Lead Information</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $lead->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company</label>
                        <input type="text" name="company" class="form-control" value="{{ old('company', $lead->company ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $lead->phone ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['new','contacted','qualified','converted','lost'] as $s)
                            <option value="{{ $s }}" {{ old('status', $lead->status ?? 'new') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lead Source</label>
                        <select name="source" class="form-select">
                            <option value="">— Select Source —</option>
                            @foreach(['Website','Referral','Cold Call','Trade Show','Social Media','Email Campaign','Partner','Other'] as $src)
                            <option value="{{ $src }}" {{ old('source', $lead->source ?? '') === $src ? 'selected' : '' }}>{{ $src }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Follow-up Date</label>
                        <input type="date" name="follow_up_date" class="form-control datepicker"
                               value="{{ old('follow_up_date', $lead->follow_up_date?->format('Y-m-d') ?? now()->addDays(3)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Lead details, conversation history...">{{ old('notes', $lead->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-user-gear" style="color:var(--purple)"></i> Assignment</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select select2">
                            <option value="">— Unassigned —</option>
                            @foreach($users ?? \App\Models\User::where('is_active',true)->get() as $u)
                            <option value="{{ $u->id }}" {{ old('assigned_to', $lead->assigned_to ?? '') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Program / Campaign</label>
                        <input type="text" name="program" class="form-control"
                               value="{{ old('program', $lead->program ?? '') }}" placeholder="Campaign name...">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="fa-solid fa-save"></i> {{ isset($lead->id) ? 'Update Lead' : 'Create Lead' }}
        </button>
    </div>
</div>
</form>
@endsection

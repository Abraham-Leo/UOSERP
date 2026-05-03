@extends('layouts.app')
@section('title', 'Opportunity')
@section('breadcrumb')
    <a href="{{ route('crm.opportunities.index') }}" style="color:var(--text-muted);text-decoration:none">CRM / Opportunities</a> / <span class="current">{{ isset($opportunity->id) ? 'Edit' : 'New' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($opportunity->id) ? route('crm.opportunities.update',$opportunity) : route('crm.opportunities.store') }}">
@csrf
@if(isset($opportunity->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($opportunity->id) ? 'Edit Opportunity' : 'New Opportunity' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('crm.opportunities.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><div class="card-title">Opportunity Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select select2" required>
                            <option value="">— Select Customer —</option>
                            @foreach(\App\Models\Customer::active()->get() as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $opportunity->customer_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Opportunity Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $opportunity->title ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['open','qualified','proposal','negotiation','won','lost'] as $s)
                            <option value="{{ $s }}" {{ old('status', $opportunity->status ?? 'open') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estimated Value ($)</label>
                        <input type="number" name="value" class="form-control" value="{{ old('value', $opportunity->value ?? 0) }}" step="0.01" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Probability (%)</label>
                        <input type="number" name="probability" class="form-control" value="{{ old('probability', $opportunity->probability ?? 50) }}" min="0" max="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Target Close Date</label>
                        <input type="date" name="target_date" class="form-control datepicker" value="{{ old('target_date', $opportunity->target_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select select2">
                            <option value="">— Unassigned —</option>
                            @foreach(\App\Models\User::where('is_active',true)->get() as $u)
                            <option value="{{ $u->id }}" {{ old('assigned_to', $opportunity->assigned_to ?? '') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="4">{{ old('notes', $opportunity->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save Opportunity</button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

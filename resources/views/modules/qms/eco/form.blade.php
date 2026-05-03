@extends('layouts.app')
@section('title', isset($eco->id) ? 'Edit ECO' : 'New ECO')
@section('breadcrumb')
    <a href="{{ route('qms.eco.index') }}" style="color:var(--text-muted);text-decoration:none">Quality / ECO</a> /
    <span class="current">{{ isset($eco->id) ? $eco->eco_number : 'New ECO' }}</span>
@endsection
@section('content')
@php $eco = $eco ?? null; @endphp
<form method="POST" action="{{ isset($eco->id) ? route('qms.eco.update',$eco) : route('qms.eco.store') }}">
@csrf
@if(isset($eco->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-code-branch" style="color:var(--accent);margin-right:10px"></i>
        {{ isset($eco->id) ? 'Edit: '.$eco->eco_number : 'New Engineering Change Order' }}
    </h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('qms.eco.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save ECO</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--accent)"></i> ECO Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Title <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $eco->title ?? '') }}" required
                               placeholder="Brief description of the change...">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="eco" {{ old('type', $eco->type ?? 'eco') === 'eco' ? 'selected' : '' }}>ECO — Engineering Change Order</option>
                            <option value="ecr" {{ old('type', $eco->type ?? 'eco') === 'ecr' ? 'selected' : '' }}>ECR — Engineering Change Request</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description / Change Details <span style="color:var(--danger)">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4" required placeholder="Describe the change in detail...">{{ old('description', $eco->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Risk Mitigation</label>
                        <textarea name="risk_mitigation" class="form-control" rows="2"
                                  placeholder="How risks from this change will be mitigated...">{{ old('risk_mitigation', $eco->risk_mitigation ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Affected Part</label>
                        <select name="part_id" class="form-select select2">
                            <option value="">— No specific part —</option>
                            @foreach($parts ?? \App\Models\Part::where('is_active',true)->get() as $p)
                            <option value="{{ $p->id }}" {{ old('part_id', $eco->part_id ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->part_number }} — {{ $p->description }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Revision From</label>
                        <input type="text" name="rev_from" class="form-control"
                               value="{{ old('rev_from', $eco->rev_from ?? '') }}" placeholder="A">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Revision To</label>
                        <input type="text" name="rev_to" class="form-control"
                               value="{{ old('rev_to', $eco->rev_to ?? '') }}" placeholder="B">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cost Impact ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="cost_impact" class="form-control"
                                   value="{{ old('cost_impact', $eco->cost_impact ?? 0) }}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['draft','review','approved','closed'] as $s)
                            <option value="{{ $s }}" {{ old('status', $eco->status ?? 'draft') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control datepicker"
                               value="{{ old('due_date', $eco->due_date?->format('Y-m-d') ?? now()->addDays(14)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="Any additional notes or context...">{{ old('notes', $eco->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-users" style="color:var(--purple)"></i> Responsibilities</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Initiated By</label>
                        <select name="initiated_by" class="form-select select2">
                            <option value="">— Select User —</option>
                            @foreach($users ?? \App\Models\User::where('is_active',true)->get() as $u)
                            <option value="{{ $u->id }}" {{ old('initiated_by', $eco->initiated_by ?? auth()->id()) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select select2">
                            <option value="">— Unassigned —</option>
                            @foreach($users ?? \App\Models\User::where('is_active',true)->get() as $u)
                            <option value="{{ $u->id }}" {{ old('assigned_to', $eco->assigned_to ?? '') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-clipboard-list" style="color:var(--warning)"></i> Approval Checklist</div></div>
            <div class="card-body">
                @foreach(['MFG Engineering','Sales','Purchasing','Quality','Document Control'] as $dept)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
                    <span style="font-size:13px">{{ $dept }}</span>
                    <span class="badge badge-secondary" style="font-size:10px">Pending</span>
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="fa-solid fa-save"></i> {{ isset($eco->id) ? 'Update ECO' : 'Create ECO' }}
        </button>
    </div>
</div>
</form>
@endsection

@extends('layouts.app')
@section('title', isset($asset->id) ? 'Edit Asset' : 'New Asset')
@section('breadcrumb')
    <a href="{{ route('tools.assets.index') }}" style="color:var(--text-muted);text-decoration:none">Admin / Tools & Equipment</a> /
    <span class="current">{{ isset($asset->id) ? $asset->name : 'New Asset' }}</span>
@endsection
@section('content')
@php $asset = $asset ?? null; @endphp
<form method="POST" action="{{ isset($asset->id) ? route('tools.assets.update',$asset) : route('tools.assets.store') }}">
@csrf
@if(isset($asset->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-screwdriver-wrench" style="color:var(--accent);margin-right:10px"></i>
        {{ isset($asset->id) ? 'Edit: '.$asset->name : 'New Tool / Asset' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('tools.assets.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Asset</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Asset Information</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Asset ID <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="asset_id" class="form-control"
                               value="{{ old('asset_id', $asset->asset_id ?? '') }}" required placeholder="TOOL-001...">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Name / Description <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $asset->name ?? '') }}" required placeholder="e.g. Torque Wrench 50Nm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            @foreach(['tool','machine','equipment'] as $t)
                            <option value="{{ $t }}" {{ old('type', $asset->type ?? 'tool') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Manufacturer</label>
                        <input type="text" name="manufacturer" class="form-control"
                               value="{{ old('manufacturer', $asset->manufacturer ?? '') }}" placeholder="Brand/Make...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Model Number</label>
                        <input type="text" name="model_number" class="form-control"
                               value="{{ old('model_number', $asset->model_number ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control"
                               value="{{ old('serial_number', $asset->serial_number ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Location / Bin</label>
                        <input type="text" name="bin_location" class="form-control"
                               value="{{ old('bin_location', $asset->bin_location ?? '') }}" placeholder="A-1-01...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Owner</label>
                        <input type="text" name="owner" class="form-control"
                               value="{{ old('owner', $asset->owner ?? '') }}" placeholder="Department or person...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['available','checked_out','maintenance'] as $s)
                            <option value="{{ $s }}" {{ old('status', $asset->status ?? 'available') === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ',$s)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Additional notes about this asset...">{{ old('notes', $asset->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-calendar-check" style="color:var(--warning)"></i> Purchase & Maintenance</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Purchase Value ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="purchase_value" class="form-control"
                                   value="{{ old('purchase_value', $asset->purchase_value ?? 0) }}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control datepicker"
                               value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Maintenance Frequency (days)</label>
                        <input type="number" name="maintenance_frequency_days" class="form-control"
                               value="{{ old('maintenance_frequency_days', $asset->maintenance_frequency_days ?? 365) }}" min="1">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Next Maintenance Date</label>
                        <input type="date" name="next_maintenance_date" class="form-control datepicker"
                               value="{{ old('next_maintenance_date', $asset->next_maintenance_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-12">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $asset->is_active ?? true) ? 'checked' : '' }}
                                   style="width:18px;height:18px;accent-color:var(--accent)">
                            <span style="font-size:13.5px;font-weight:500">Active Asset</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="fa-solid fa-save"></i> {{ isset($asset->id) ? 'Update Asset' : 'Create Asset' }}
        </button>
    </div>
</div>
</form>
@endsection

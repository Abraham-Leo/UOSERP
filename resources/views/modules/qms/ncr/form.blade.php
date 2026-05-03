@extends('layouts.app')
@section('title', isset($ncr->id) ? 'Edit NCR' : 'New NCR')
@section('breadcrumb')
    <a href="{{ route('qms.ncr.index') }}" style="color:var(--text-muted);text-decoration:none">Quality / NCR</a> /
    <span class="current">{{ isset($ncr->id) ? $ncr->ncr_number : 'New NCR' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($ncr->id) ? route('qms.ncr.update',$ncr) : route('qms.ncr.store') }}">
@csrf
@if(isset($ncr->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($ncr->id) ? 'Edit NCR: '.$ncr->ncr_number : 'New Non-Conformance Report' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('qms.ncr.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-save"></i> Save NCR</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Issue Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Title / Issue Summary *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title',$ncr->title ?? '') }}" required placeholder="Brief description of the non-conformance..."></div>
                    <div class="col-12"><label class="form-label">Detailed Description *</label>
                        <textarea name="description" class="form-control" rows="4" required placeholder="Describe the non-conformance in detail...">{{ old('description',$ncr->description ?? '') }}</textarea></div>
                    <div class="col-md-4"><label class="form-label">Source</label>
                        <select name="source" class="form-select">
                            @foreach(['receiving','production','customer','audit','inspection'] as $s)
                            <option {{ old('source',$ncr->source??'receiving')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['open','review','mrb','closed'] as $s)
                            <option {{ old('status',$ncr->status??'open')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Disposition</label>
                        <select name="disposition" class="form-select">
                            <option value="">— Pending —</option>
                            @foreach(['scrap','rework','use_as_is','return_to_vendor','repair'] as $d)
                            <option value="{{ $d }}" {{ old('disposition',$ncr->disposition ?? '')===$d?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$d)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label">Affected Part</label>
                        <select name="part_id" class="form-select select2">
                            <option value="">— Select Part —</option>
                            @foreach($parts ?? [] as $p)
                            <option value="{{ $p->id }}" {{ old('part_id',$ncr->part_id ?? '')==$p->id?'selected':'' }}>{{ $p->part_number }} — {{ $p->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label">Quantity Non-Conforming</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity',$ncr->quantity ?? 0) }}" min="0" step="0.001"></div>
                    <div class="col-md-3"><label class="form-label">Cost Impact ($)</label>
                        <div class="input-group"><span class="input-group-text">$</span>
                        <input type="number" name="cost_impact" class="form-control" value="{{ old('cost_impact',$ncr->cost_impact ?? 0) }}" step="0.01" min="0"></div></div>
                    <div class="col-12"><label class="form-label">Containment Area / Action</label>
                        <input type="text" name="containment_area" class="form-control" value="{{ old('containment_area',$ncr->containment_area ?? '') }}" placeholder="Area quarantined, action taken immediately..."></div>
                    <div class="col-12"><label class="form-label">Resolution (if closing)</label>
                        <textarea name="resolution" class="form-control" rows="3" placeholder="How was the NCR resolved?">{{ old('resolution',$ncr->resolution ?? '') }}</textarea></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Assignment</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select select2">
                            <option value="">— Unassigned —</option>
                            @foreach($users ?? [] as $u)
                            <option value="{{ $u->id }}" {{ old('assigned_to',$ncr->assigned_to ?? '')==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Vendor (if applicable)</label>
                        <select name="vendor_id" class="form-select select2">
                            <option value="">— None —</option>
                            @foreach($vendors ?? [] as $v)
                            <option value="{{ $v->id }}" {{ old('vendor_id',$ncr->vendor_id ?? '')==$v->id?'selected':'' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control datepicker" value="{{ old('due_date',$ncr->due_date?->format('Y-m-d') ?? now()->addDays(7)->format('Y-m-d')) }}"></div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-danger w-100 btn-lg"><i class="fa-solid fa-save"></i> Save NCR</button>
    </div>
</div>
</form>
@endsection

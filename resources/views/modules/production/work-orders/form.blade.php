@extends('layouts.app')
@section('title', isset($wo->id) ? 'Edit Work Order' : 'New Work Order')
@section('breadcrumb')
    <a href="{{ route('production.work-orders.index') }}" style="color:var(--text-muted);text-decoration:none">Production / Work Orders</a> /
    <span class="current">{{ isset($wo->id) ? $wo->wo_number : 'New Work Order' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($wo->id) ? route('production.work-orders.update',$wo) : route('production.work-orders.store') }}">
@csrf
@if(isset($wo->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($wo->id) ? 'Edit: '.$wo->wo_number : 'New Work Order' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('production.work-orders.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Work Order</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Work Order Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Part / Product *</label>
                        <select name="part_id" class="form-select select2" required>
                            <option value="">— Select Part —</option>
                            @foreach($parts ?? [] as $p)
                            <option value="{{ $p->id }}" {{ old('part_id',$wo->part_id ?? request('part_id'))==$p->id?'selected':'' }}>{{ $p->part_number }} — {{ $p->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity *</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity',$wo->quantity ?? 1) }}" min="0.0001" step="0.0001" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            @foreach(['customer'=>'Customer Order','build_to_stock'=>'Build to Stock','rework'=>'Rework'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('type',$wo->type??'customer')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['open','released','in_progress','complete','cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status',$wo->status??'open')===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Order Date *</label>
                        <input type="date" name="order_date" class="form-control datepicker" value="{{ old('order_date',$wo->order_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Work Start Date</label>
                        <input type="date" name="work_start_date" class="form-control datepicker" value="{{ old('work_start_date',$wo->work_start_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control datepicker" value="{{ old('due_date',$wo->due_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Linked Sales Order</label>
                        <select name="order_id" class="form-select select2">
                            <option value="">— None —</option>
                            @foreach($orders ?? [] as $o)
                            <option value="{{ $o->id }}" {{ old('order_id',$wo->order_id ?? request('order_id'))==$o->id?'selected':'' }}>{{ $o->order_number }} — {{ $o->customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">BOM</label>
                        <select name="bom_id" class="form-select select2">
                            <option value="">— Auto from Part —</option>
                            @foreach($boms ?? [] as $b)
                            <option value="{{ $b->id }}" {{ old('bom_id',$wo->bom_id)==$b->id?'selected':'' }}>{{ $b->parentPart->part_number }} Rev {{ $b->revision }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes / Shop Instructions</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Instructions for the shop floor...">{{ old('notes',$wo->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Cost Estimates</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Unit Cost Estimate ($)</label>
                        <input type="number" name="unit_cost_estimate" class="form-control" value="{{ old('unit_cost_estimate',$wo->unit_cost_estimate ?? 0) }}" step="0.01" min="0"></div>
                    <div class="col-12"><label class="form-label">Labor Hrs Estimate</label>
                        <input type="number" name="labor_hrs_estimate" class="form-control" value="{{ old('labor_hrs_estimate',$wo->labor_hrs_estimate ?? 0) }}" step="0.01" min="0"></div>
                </div>
                <div style="margin-top:20px">
                    <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save Work Order</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

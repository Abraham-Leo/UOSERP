@extends('layouts.app')
@section('title', isset($inspection->id) ? 'Edit Inspection' : 'New Inspection')
@section('breadcrumb')
    <a href="{{ route('qms.inspections.index') }}" style="color:var(--text-muted);text-decoration:none">Quality / Inspections</a> /
    <span class="current">{{ isset($inspection->id) ? 'Edit' : 'New Inspection' }}</span>
@endsection
@section('content')
@php $inspection = $inspection ?? null; @endphp
<form method="POST" action="{{ isset($inspection->id) ? route('qms.inspections.update',$inspection) : route('qms.inspections.store') }}">
@csrf
@if(isset($inspection->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-magnifying-glass-chart" style="color:var(--accent);margin-right:10px"></i>
        {{ isset($inspection->id) ? 'Edit Inspection' : 'New Inspection' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('qms.inspections.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Inspection</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Inspection Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            @foreach(['incoming'=>'Incoming / Receiving','in_process'=>'In-Process','final'=>'Final','first_article'=>'First Article'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('type',$inspection->type??'incoming')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Result</label>
                        <select name="result" class="form-select">
                            @foreach(['pending','pass','fail','conditional'] as $r)
                            <option value="{{ $r }}" {{ old('result',$inspection->result??'pending')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Inspection Date</label>
                        <input type="date" name="inspection_date" class="form-control datepicker"
                               value="{{ old('inspection_date',$inspection->inspection_date?->format('Y-m-d')??now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Part</label>
                        <select name="part_id" class="form-select select2">
                            <option value="">— Select Part —</option>
                            @foreach($parts ?? \App\Models\Part::where('is_active',true)->get() as $p)
                            <option value="{{ $p->id }}" {{ old('part_id',$inspection->part_id??'')==$p->id?'selected':'' }}>{{ $p->part_number }} — {{ $p->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Inspector</label>
                        <select name="inspector_id" class="form-select select2">
                            <option value="">— Select Inspector —</option>
                            @foreach($users ?? \App\Models\User::where('is_active',true)->get() as $u)
                            <option value="{{ $u->id }}" {{ old('inspector_id',$inspection->inspector_id??auth()->id())==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Qty Inspected</label>
                        <input type="number" name="qty_inspected" class="form-control" value="{{ old('qty_inspected',$inspection->qty_inspected??0) }}" min="0" step="1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Qty Accepted</label>
                        <input type="number" name="qty_accepted" class="form-control" value="{{ old('qty_accepted',$inspection->qty_accepted??0) }}" min="0" step="1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Qty Rejected</label>
                        <input type="number" name="qty_rejected" class="form-control" value="{{ old('qty_rejected',$inspection->qty_rejected??0) }}" min="0" step="1">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Inspection Notes / Findings</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Describe inspection findings, measurements, observations...">{{ old('notes',$inspection->notes??'') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-link" style="color:var(--info)"></i> Links</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Linked Work Order</label>
                        <select name="work_order_id" class="form-select select2">
                            <option value="">— None —</option>
                            @foreach($workOrders ?? \App\Models\WorkOrder::whereIn('status',['in_progress','released'])->get() as $wo)
                            <option value="{{ $wo->id }}" {{ old('work_order_id',$inspection->work_order_id??'')==$wo->id?'selected':'' }}>{{ $wo->wo_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Linked PO Receipt</label>
                        <select name="receipt_id" class="form-select select2">
                            <option value="">— None —</option>
                            @foreach($receipts ?? \App\Models\Receipt::latest()->limit(50)->get() as $r)
                            <option value="{{ $r->id }}" {{ old('receipt_id',$inspection->receipt_id??'')==$r->id?'selected':'' }}>{{ $r->receipt_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info" style="font-size:12.5px;margin-bottom:16px">
            <i class="fa-solid fa-info-circle"></i>
            If inspection fails, you can automatically create an NCR from the inspection record.
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="fa-solid fa-save"></i> Save Inspection
        </button>
    </div>
</div>
</form>
@endsection

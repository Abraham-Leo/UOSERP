@extends('layouts.app')
@section('title', isset($rma->id) ? 'Edit RMA' : 'New RMA')
@section('breadcrumb')
    <a href="{{ route('shipping.rma.index') }}" style="color:var(--text-muted);text-decoration:none">Shipping / RMA</a> /
    <span class="current">{{ isset($rma->id) ? $rma->rma_number : 'New RMA' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($rma->id) ? route('shipping.rma.update',$rma) : route('shipping.rma.store') }}">
@csrf
@if(isset($rma->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-rotate-left" style="color:var(--warning);margin-right:10px"></i>
        {{ isset($rma->id) ? 'Edit: '.$rma->rma_number : 'New RMA' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('shipping.rma.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save RMA</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">RMA Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Customer <span style="color:var(--danger)">*</span></label>
                        <select name="customer_id" class="form-select select2" required>
                            <option value="">— Select Customer —</option>
                            @foreach($customers ?? \App\Models\Customer::active()->get() as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $rma->customer_id ?? request('customer_id')) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Linked Order</label>
                        <select name="order_id" class="form-select select2">
                            <option value="">— None —</option>
                            @foreach($orders ?? \App\Models\Order::latest()->limit(100)->get() as $o)
                            <option value="{{ $o->id }}" {{ old('order_id', $rma->order_id ?? request('order_id')) == $o->id ? 'selected' : '' }}>
                                {{ $o->order_number }} — {{ $o->customer->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">RMA Type</label>
                        <select name="type" class="form-select">
                            @foreach(['return'=>'Return','repair'=>'Repair','replacement'=>'Replacement','refund'=>'Refund'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('type', $rma->type ?? 'return') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['open','received','processing','closed'] as $s)
                            <option value="{{ $s }}" {{ old('status', $rma->status ?? 'open') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">RMA Date</label>
                        <input type="date" name="rma_date" class="form-control datepicker"
                               value="{{ old('rma_date', $rma->rma_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Handling Charges ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="handling_charges" class="form-control"
                                   value="{{ old('handling_charges', $rma->handling_charges ?? 0) }}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Credit Amount ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="credit_amount" class="form-control"
                                   value="{{ old('credit_amount', $rma->credit_amount ?? 0) }}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Reason for Return</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Describe reason for return...">{{ old('reason', $rma->reason ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Internal Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes...">{{ old('notes', $rma->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--info)"></i> RMA Process</div></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:12px">
                    @foreach([
                        ['1','fa-file-circle-plus','Create RMA','Generate authorization'],
                        ['2','fa-boxes-packing','Receive Material','Log returned items'],
                        ['3','fa-magnifying-glass','Inspect / Process','Evaluate condition'],
                        ['4','fa-check-circle','Close / Issue Credit','Resolve the RMA'],
                    ] as [$num,$icon,$title,$desc])
                    <div style="display:flex;align-items:flex-start;gap:12px">
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--accent);flex-shrink:0">{{ $num }}</div>
                        <div>
                            <div style="font-size:13.5px;font-weight:600">{{ $title }}</div>
                            <div style="font-size:12px;color:var(--text-muted)">{{ $desc }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="fa-solid fa-save"></i> {{ isset($rma->id) ? 'Update RMA' : 'Create RMA' }}
        </button>
    </div>
</div>
</form>
@endsection

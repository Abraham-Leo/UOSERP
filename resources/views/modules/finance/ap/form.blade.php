@extends('layouts.app')
@section('title', isset($voucher->id) ? 'Edit Voucher' : 'Enter Vendor Invoice')
@section('breadcrumb')
    <a href="{{ route('finance.accounts-payable.index') }}" style="color:var(--text-muted);text-decoration:none">Finance / A/P</a> /
    <span class="current">{{ isset($voucher->id) ? $voucher->voucher_number : 'New Voucher' }}</span>
@endsection
@section('content')
@php $voucher = $voucher ?? null; @endphp
<form method="POST" action="{{ isset($voucher->id) ? route('finance.accounts-payable.update',$voucher) : route('finance.accounts-payable.store') }}">
@csrf
@if(isset($voucher->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-file-invoice" style="color:var(--purple);margin-right:10px"></i>
        {{ isset($voucher->id) ? 'Edit Voucher: '.$voucher->voucher_number : 'Enter Vendor Invoice' }}
    </h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('finance.accounts-payable.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Voucher</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--purple)"></i> Voucher Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Vendor <span style="color:var(--danger)">*</span></label>
                        <select name="vendor_id" class="form-select select2" required>
                            <option value="">— Select Vendor —</option>
                            @foreach($vendors ?? \App\Models\Vendor::where('is_active',true)->get() as $v)
                            <option value="{{ $v->id }}" {{ old('vendor_id', $voucher->vendor_id ?? '') == $v->id ? 'selected' : '' }}>
                                {{ $v->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Purchase Order (Optional)</label>
                        <select name="purchase_order_id" class="form-select select2">
                            <option value="">— None —</option>
                            @foreach($purchaseOrders ?? \App\Models\PurchaseOrder::latest()->limit(100)->get() as $po)
                            <option value="{{ $po->id }}" {{ old('purchase_order_id', $voucher->purchase_order_id ?? '') == $po->id ? 'selected' : '' }}>
                                {{ $po->po_number }} — {{ $po->vendor->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Vendor Invoice # <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="vendor_invoice_number" class="form-control"
                               value="{{ old('vendor_invoice_number', $voucher->vendor_invoice_number ?? '') }}"
                               required placeholder="INV-12345...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Invoice Date <span style="color:var(--danger)">*</span></label>
                        <input type="date" name="invoice_date" class="form-control datepicker"
                               value="{{ old('invoice_date', $voucher->invoice_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Due Date <span style="color:var(--danger)">*</span></label>
                        <input type="date" name="due_date" class="form-control datepicker"
                               value="{{ old('due_date', $voucher->due_date?->format('Y-m-d') ?? now()->addDays(30)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount ($) <span style="color:var(--danger)">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="amount" class="form-control"
                                   value="{{ old('amount', $voucher->amount ?? 0) }}" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount Paid ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="amount_paid" class="form-control"
                                   value="{{ old('amount_paid', $voucher->amount_paid ?? 0) }}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['pending','approved','paid','cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $voucher->status ?? 'pending') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">GL Account</label>
                        <select name="gl_account" class="form-select">
                            <option value="">— Auto from Vendor —</option>
                            @foreach($glAccounts ?? \App\Models\GlAccount::where('is_active',true)->get() as $acc)
                            <option value="{{ $acc->account_number }}" {{ old('gl_account', $voucher->gl_account ?? '') === $acc->account_number ? 'selected' : '' }}>
                                {{ $acc->account_number }} — {{ $acc->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Invoice notes...">{{ old('notes', $voucher->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-triangle-exclamation" style="color:var(--warning)"></i> Three-Way Match</div></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px">
                    @foreach([
                        ['PO Amount','—','text-muted'],
                        ['Receipt Amount','—','text-muted'],
                        ['Invoice Amount','—','text-muted'],
                        ['Match Status','Pending','warning'],
                    ] as [$l,$v,$c])
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;padding:6px 0;border-bottom:1px solid var(--border)">
                        <span style="color:var(--text-muted)">{{ $l }}</span>
                        <span style="font-weight:600;color:var(--{{ $c }})">{{ $v }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="alert alert-info" style="font-size:12.5px">
                    <i class="fa-solid fa-info-circle"></i>
                    Select a PO to enable three-way match validation.
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="fa-solid fa-save"></i> {{ isset($voucher->id) ? 'Update Voucher' : 'Save Voucher' }}
        </button>
    </div>
</div>
</form>
@endsection

@extends('layouts.app')
@section('title', isset($invoice->id) ? 'Edit Invoice' : 'New Invoice')
@section('breadcrumb')
    <a href="{{ route('sales.invoices.index') }}" style="color:var(--text-muted);text-decoration:none">Sales / Invoices</a> /
    <span class="current">{{ isset($invoice->id) ? $invoice->invoice_number : 'New Invoice' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($invoice->id) ? route('sales.invoices.update',$invoice) : route('sales.invoices.store') }}">
@csrf
@if(isset($invoice->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($invoice->id) ? 'Edit: '.$invoice->invoice_number : 'New Invoice' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.invoices.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Invoice</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><div class="card-title">Invoice Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select select2" required>
                            <option value="">— Select Customer —</option>
                            @foreach($customers ?? [] as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id',$invoice->customer_id ?? request('customer_id'))==$c->id?'selected':'' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Invoice Date *</label>
                        <input type="date" name="invoice_date" class="form-control datepicker" value="{{ old('invoice_date',$invoice->invoice_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control datepicker" value="{{ old('due_date',$invoice->due_date?->format('Y-m-d') ?? now()->addDays(30)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['draft','sent','paid','overdue','cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status',$invoice->status??'draft')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Terms</label>
                        <select name="payment_terms" class="form-select">
                            @foreach(['Net 15','Net 30','Net 45','Net 60','COD','Prepay'] as $t)
                            <option {{ old('payment_terms',$invoice->payment_terms??'Net 30')===$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Linked Order</label>
                        <select name="order_id" class="form-select select2">
                            <option value="">— None —</option>
                            @foreach($orders ?? [] as $o)
                            <option value="{{ $o->id }}" {{ old('order_id',$invoice->order_id ?? request('order_id'))==$o->id?'selected':'' }}>{{ $o->order_number }} – {{ $o->customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes',$invoice->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Totals</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Subtotal ($)</label>
                        <input type="number" name="subtotal" class="form-control" value="{{ old('subtotal',$invoice->subtotal ?? 0) }}" step="0.01" min="0">
                    </div>
                    <div class="col-12"><label class="form-label">Tax Amount ($)</label>
                        <input type="number" name="tax_amount" class="form-control" value="{{ old('tax_amount',$invoice->tax_amount ?? 0) }}" step="0.01" min="0">
                    </div>
                    <div class="col-12"><label class="form-label">Shipping ($)</label>
                        <input type="number" name="shipping" class="form-control" value="{{ old('shipping',$invoice->shipping ?? 0) }}" step="0.01" min="0">
                    </div>
                    <div class="col-12"><label class="form-label">Total ($)</label>
                        <input type="number" name="total" class="form-control" value="{{ old('total',$invoice->total ?? 0) }}" step="0.01" min="0">
                    </div>
                    <div class="col-12"><label class="form-label">Amount Paid ($)</label>
                        <input type="number" name="amount_paid" class="form-control" value="{{ old('amount_paid',$invoice->amount_paid ?? 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <div style="margin-top:16px">
                    <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save Invoice</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

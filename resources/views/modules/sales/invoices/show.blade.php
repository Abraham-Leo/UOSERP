@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('breadcrumb')
    <a href="{{ route('sales.invoices.index') }}" style="color:var(--text-muted);text-decoration:none">Sales / Invoices</a> /
    <span class="current">{{ $invoice->invoice_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $invoice->invoice_number }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <x-status-badge :status="$invoice->status" />
            <span style="font-size:13px;color:var(--text-muted)">{{ $invoice->customer->name }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.invoices.pdf',$invoice) }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-file-pdf"></i> PDF</a>
        @if($invoice->status !== 'paid')
        <form method="POST" action="{{ route('sales.invoices.send',$invoice) }}">@csrf
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Send to Customer</button>
        </form>
        @endif
        <a href="{{ route('sales.invoices.edit',$invoice) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Invoice Lines</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>#</th><th>Part #</th><th>Description</th><th style="text-align:right">Qty</th><th style="text-align:right">Unit Price</th><th style="text-align:right">Total</th></tr></thead>
                <tbody>
                    @foreach($invoice->lines ?? [] as $i => $line)
                    <tr>
                        <td style="color:var(--text-muted);font-size:12px">{{ $i+1 }}</td>
                        <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">{{ $line->part->part_number ?? '-' }}</td>
                        <td>{{ $line->description ?? $line->part->description ?? '-' }}</td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($line->quantity ?? 0,0) }}</td>
                        <td style="text-align:right;font-family:monospace">${{ number_format($line->unit_price ?? 0,4) }}</td>
                        <td style="text-align:right;font-weight:600">${{ number_format($line->line_total ?? 0,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><td colspan="5" style="text-align:right;padding:8px 14px;color:var(--text-muted)">Subtotal</td><td style="text-align:right;padding:8px 14px;font-weight:600">${{ number_format($invoice->subtotal,2) }}</td></tr>
                    @if($invoice->tax_amount > 0)
                    <tr><td colspan="5" style="text-align:right;padding:4px 14px;color:var(--text-muted)">Tax</td><td style="text-align:right;padding:4px 14px">${{ number_format($invoice->tax_amount,2) }}</td></tr>
                    @endif
                    <tr style="background:var(--accent-soft)"><td colspan="5" style="text-align:right;font-weight:700;font-size:15px;padding:12px 14px">TOTAL</td><td style="text-align:right;font-weight:700;font-size:15px;color:var(--accent);padding:12px 14px">${{ number_format($invoice->total,2) }}</td></tr>
                    <tr><td colspan="5" style="text-align:right;padding:6px 14px;color:var(--text-muted)">Amount Paid</td><td style="text-align:right;padding:6px 14px;color:var(--success);font-weight:600">${{ number_format($invoice->amount_paid,2) }}</td></tr>
                    <tr><td colspan="5" style="text-align:right;padding:6px 14px;font-weight:600">Balance Due</td><td style="text-align:right;padding:6px 14px;font-weight:700;color:{{ 'var(--'.($invoice->balance_due > 0 ? 'danger' : 'success').')'  }}">${{ number_format($invoice->balance_due,2) }}</td></tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Invoice Info</div></div>
            <div class="card-body">
                @foreach([
                    ['Invoice #',$invoice->invoice_number],
                    ['Customer',$invoice->customer->name],
                    ['Invoice Date',$invoice->invoice_date->format('M d, Y')],
                    ['Due Date',$invoice->due_date?->format('M d, Y') ?? '—'],
                    ['Terms',$invoice->payment_terms],
                    ['Currency',$invoice->currency],
                    ['Sent',$invoice->sent_at?->format('M d, Y H:i') ?? 'Not sent'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @if($invoice->order)
        <div class="card">
            <div class="card-header"><div class="card-title">Linked Order</div></div>
            <div class="card-body">
                <a href="{{ route('sales.orders.show',$invoice->order) }}" style="color:var(--accent);font-family:monospace;font-weight:600">{{ $invoice->order->order_number }}</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

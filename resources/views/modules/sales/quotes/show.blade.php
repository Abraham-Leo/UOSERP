@extends('layouts.app')
@section('title', $quote->quote_number)
@section('breadcrumb')
    <a href="{{ route('sales.quotes.index') }}" style="color:var(--text-muted);text-decoration:none">Sales / Quotes</a> /
    <span class="current">{{ $quote->quote_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $quote->quote_number }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <x-status-badge :status="$quote->status" />
            <span style="font-size:13px;color:var(--text-muted)">{{ $quote->customer->name }}</span>
            <span style="font-size:13px;color:var(--text-muted)">· Created {{ $quote->created_at->format('M d, Y') }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.quotes.pdf', $quote) }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-file-pdf"></i> PDF</a>
        <a href="{{ route('sales.quotes.edit', $quote) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
        @if(in_array($quote->status, ['draft','sent']))
        <form method="POST" action="{{ route('sales.quotes.convert', $quote) }}" onsubmit="return confirm('Convert to Sales Order?')">
            @csrf
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-arrow-right-to-bracket"></i> Convert to Order</button>
        </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Line Items --}}
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Line Items</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>#</th><th>Part</th><th>Description</th><th style="text-align:right">Qty</th><th style="text-align:right">Unit Price</th><th style="text-align:right">Disc</th><th style="text-align:right">Total</th></tr></thead>
                <tbody>
                    @foreach($quote->lines as $i => $line)
                    <tr>
                        <td style="color:var(--text-muted);font-size:12px">{{ $i+1 }}</td>
                        <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">{{ $line->part->part_number }}</td>
                        <td>{{ $line->part->description }}</td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($line->quantity,0) }}</td>
                        <td style="text-align:right;font-family:monospace">${{ number_format($line->unit_price,4) }}</td>
                        <td style="text-align:right;color:var(--text-muted)">{{ $line->discount_pct > 0 ? number_format($line->discount_pct*100,1).'%' : '—' }}</td>
                        <td style="text-align:right;font-weight:600">${{ number_format($line->line_total,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><td colspan="6" style="text-align:right;color:var(--text-muted);font-size:13px;padding:10px 14px">Subtotal</td><td style="text-align:right;font-weight:600;padding:10px 14px">${{ number_format($quote->subtotal,2) }}</td></tr>
                    @if($quote->shipping_cost > 0)
                    <tr><td colspan="6" style="text-align:right;color:var(--text-muted);font-size:13px;padding:6px 14px">Shipping</td><td style="text-align:right;padding:6px 14px">${{ number_format($quote->shipping_cost,2) }}</td></tr>
                    @endif
                    @if($quote->tax_amount > 0)
                    <tr><td colspan="6" style="text-align:right;color:var(--text-muted);font-size:13px;padding:6px 14px">Tax ({{ number_format($quote->tax_rate*100,2) }}%)</td><td style="text-align:right;padding:6px 14px">${{ number_format($quote->tax_amount,2) }}</td></tr>
                    @endif
                    <tr style="background:var(--accent-soft)"><td colspan="6" style="text-align:right;font-weight:700;font-size:15px;padding:12px 14px">TOTAL</td><td style="text-align:right;font-weight:700;font-size:15px;color:var(--accent);padding:12px 14px">${{ number_format($quote->total,2) }}</td></tr>
                </tfoot>
            </table>
        </div>
        @if($quote->notes)
        <div class="card"><div class="card-header"><div class="card-title">Notes</div></div><div class="card-body" style="font-size:13.5px;color:var(--text-muted);white-space:pre-line">{{ $quote->notes }}</div></div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Quote Info</div></div>
            <div class="card-body">
                @foreach([
                    ['Quote #', $quote->quote_number],
                    ['Customer', $quote->customer->name],
                    ['Date', $quote->quote_date->format('M d, Y')],
                    ['Expiry', $quote->expiry_date?->format('M d, Y') ?? '—'],
                    ['Terms', $quote->payment_terms],
                    ['Currency', $quote->currency],
                    ['Probability', $quote->probability.'%'],
                    ['Customer PO', $quote->customer_po ?? '—'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @if($quote->order)
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            Converted to <a href="{{ route('sales.orders.show', $quote->order) }}" style="font-weight:600">{{ $quote->order->order_number }}</a>
        </div>
        @endif
    </div>
</div>
@endsection

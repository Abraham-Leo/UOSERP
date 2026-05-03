@extends('layouts.app')
@section('title', $po->po_number)
@section('breadcrumb')
    <a href="{{ route('purchasing.purchase-orders.index') }}" style="color:var(--text-muted);text-decoration:none">Purchasing / POs</a> /
    <span class="current">{{ $po->po_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $po->po_number }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <x-status-badge :status="$po->status" />
            <span style="font-size:13px;color:var(--text-muted)">{{ $po->vendor->name }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchasing.purchase-orders.pdf',$po) }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-file-pdf"></i> PDF</a>
        @if(!$po->acknowledged)
        <form method="POST" action="{{ route('purchasing.purchase-orders.acknowledge',$po) }}">@csrf
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Mark Acknowledged</button>
        </form>
        @endif
        <a href="{{ route('purchasing.purchase-orders.edit',$po) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
        <a href="{{ route('purchasing.receiving.create') }}?po_id={{ $po->id }}" class="btn btn-primary"><i class="fa-solid fa-boxes-packing"></i> Receive Items</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Line Items</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>#</th><th>Part #</th><th>Description</th><th style="text-align:right">Qty</th><th style="text-align:right">Rcvd</th><th style="text-align:right">Unit Cost</th><th style="text-align:right">Total</th><th>Commit Date</th></tr></thead>
                <tbody>
                    @foreach($po->lines as $i => $line)
                    <tr>
                        <td style="color:var(--text-muted);font-size:12px">{{ $i+1 }}</td>
                        <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">{{ $line->part->part_number }}</td>
                        <td>{{ $line->part->description }}</td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($line->quantity,0) }}</td>
                        <td style="text-align:right;font-family:monospace;color:{{ $line->qty_received >= $line->quantity ? 'var(--success)' : 'var(--warning)' }}">{{ number_format($line->qty_received,0) }}</td>
                        <td style="text-align:right;font-family:monospace">${{ number_format($line->unit_cost,4) }}</td>
                        <td style="text-align:right;font-weight:600">${{ number_format($line->line_total,2) }}</td>
                        <td style="font-size:12.5px;color:var(--text-muted)">{{ $line->commit_date?->format('M d, Y') ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:var(--accent-soft)"><td colspan="6" style="text-align:right;font-weight:700;font-size:15px;padding:12px 14px">TOTAL</td><td style="text-align:right;font-weight:700;font-size:15px;color:var(--accent);padding:12px 14px">${{ number_format($po->total,2) }}</td><td></td></tr>
                </tfoot>
            </table>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-boxes-packing" style="color:var(--success)"></i> Receipts</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>Receipt #</th><th>Date</th><th>Packing Slip</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse($po->receipts as $r)
                    <tr>
                        <td><a href="{{ route('purchasing.receiving.show',$r) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $r->receipt_number }}</a></td>
                        <td style="font-size:12.5px;color:var(--text-muted)">{{ $r->receipt_date->format('M d, Y') }}</td>
                        <td style="font-size:12.5px">{{ $r->packing_slip ?? '—' }}</td>
                        <td><x-status-badge :status="$r->status" /></td>
                        <td><a href="{{ route('purchasing.receiving.show',$r) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-muted)">No receipts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">PO Info</div></div>
            <div class="card-body">
                @foreach([
                    ['PO #',$po->po_number],
                    ['Vendor',$po->vendor->name],
                    ['Type',ucfirst($po->type)],
                    ['PO Date',$po->po_date->format('M d, Y')],
                    ['Requested',$po->requested_date?->format('M d, Y') ?? '—'],
                    ['Terms',$po->payment_terms],
                    ['Currency',$po->currency],
                    ['Acknowledged',$po->acknowledged ? '✅ Yes' : '❌ No'],
                    ['Billed','$'.number_format($po->amount_billed,2)],
                    ['Paid','$'.number_format($po->amount_paid,2)],
                    ['Balance','$'.number_format($po->balance,2)],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @if($po->notes)
        <div class="card">
            <div class="card-header"><div class="card-title">Notes</div></div>
            <div class="card-body" style="font-size:13.5px;color:var(--text-muted);white-space:pre-line">{{ $po->notes }}</div>
        </div>
        @endif
    </div>
</div>
@endsection

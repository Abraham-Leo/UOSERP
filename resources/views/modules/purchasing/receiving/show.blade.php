@extends('layouts.app')
@section('title', $receipt->receipt_number)
@section('breadcrumb')
    <a href="{{ route('purchasing.receiving.index') }}" style="color:var(--text-muted);text-decoration:none">Purchasing / Receiving</a> /
    <span class="current">{{ $receipt->receipt_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $receipt->receipt_number }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <x-status-badge :status="$receipt->status" />
            <span style="font-size:13px;color:var(--text-muted)">{{ $receipt->purchaseOrder->po_number }} · {{ $receipt->vendor->name }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchasing.receiving.edit',$receipt) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Received Lines</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>Part #</th><th>Description</th><th style="text-align:right">Qty</th><th style="text-align:right">Unit Cost</th><th>Lot / Date Code</th><th>Inspection</th><th>Bin</th></tr></thead>
                <tbody>
                    @foreach($receipt->lines as $line)
                    <tr>
                        <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">{{ $line->part->part_number }}</td>
                        <td>{{ $line->part->description }}</td>
                        <td style="text-align:right;font-family:monospace;font-weight:600">{{ number_format($line->quantity,0) }}</td>
                        <td style="text-align:right;font-family:monospace">${{ number_format($line->unit_cost,4) }}</td>
                        <td style="font-family:monospace;font-size:12px">{{ $line->lot_number ?? $line->date_code ?? '—' }}</td>
                        <td>
                            @if($line->inspection_status === 'accepted')<span class="badge badge-success">Accepted</span>
                            @elseif($line->inspection_status === 'rejected')<span class="badge badge-danger">Rejected</span>
                            @else<span class="badge badge-warning">Pending</span>@endif
                        </td>
                        <td style="font-size:12.5px;color:var(--text-muted)">{{ $line->binLocation->code ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Receipt Info</div></div>
            <div class="card-body">
                @foreach([
                    ['Receipt #',$receipt->receipt_number],
                    ['PO #',$receipt->purchaseOrder->po_number],
                    ['Vendor',$receipt->vendor->name],
                    ['Date',$receipt->receipt_date->format('M d, Y')],
                    ['Packing Slip',$receipt->packing_slip ?? '—'],
                    ['Received By',$receipt->received_by ?? '—'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
                @if($receipt->notes)<div style="margin-top:12px;font-size:13.5px;color:var(--text-muted)">{{ $receipt->notes }}</div>@endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Transaction History: '.$part->part_number)
@section('breadcrumb')
    <a href="{{ route('inventory.parts.index') }}" style="color:var(--text-muted);text-decoration:none">Inventory / Parts</a> /
    <a href="{{ route('inventory.parts.show',$part) }}" style="color:var(--text-muted);text-decoration:none">{{ $part->part_number }}</a> /
    <span class="current">History</span>
@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Transaction History</h1>
    <p class="page-subtitle">{{ $part->part_number }} — {{ $part->description }}</p></div>
    <a href="{{ route('inventory.parts.show',$part) }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Part</a>
</div>
<div class="card table-card">
    <div class="card-header"><div class="card-title">All Transactions</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table erp-datatable" style="width:100%">
            <thead><tr><th>Date</th><th>Type</th><th>Reference</th><th style="text-align:right">Qty In</th><th style="text-align:right">Qty Out</th><th style="text-align:right">Running Balance</th><th>Warehouse</th><th>Notes</th></tr></thead>
            <tbody>
                @forelse($transactions ?? [] as $tx)
                <tr>
                    <td style="font-family:monospace;font-size:12px">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                    <td><span class="badge badge-{{ $tx->type === 'receipt' ? 'success' : ($tx->type === 'consumed' ? 'warning' : 'info') }}">{{ ucfirst($tx->type) }}</span></td>
                    <td><a href="#" style="color:var(--accent);font-family:monospace;font-size:12px">{{ $tx->reference ?? '—' }}</a></td>
                    <td style="text-align:right;color:var(--success);font-family:monospace;font-weight:600">{{ $tx->qty_in > 0 ? '+'.number_format($tx->qty_in,2) : '—' }}</td>
                    <td style="text-align:right;color:var(--danger);font-family:monospace;font-weight:600">{{ $tx->qty_out > 0 ? '-'.number_format($tx->qty_out,2) : '—' }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:600">{{ number_format($tx->balance,2) }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $tx->warehouse->name ?? '—' }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $tx->notes ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">No transaction history found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title','Invoices')
@section('breadcrumb') Sales / <span class="current">Invoices</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-receipt" style="color:var(--accent);margin-right:10px"></i>Invoices</h1>
        <p class="page-subtitle">Manage customer invoices and receivables</p>
    </div>
    <a href="{{ route('sales.invoices.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Invoice</a>
</div>

<div class="row g-3 mb-4">
    @php
    $invStats = [
        ['Total Outstanding','$124K','accent','fa-dollar-sign'],
        ['Overdue','$18K','danger','fa-clock'],
        ['Due This Week','$42K','warning','fa-calendar'],
        ['Paid This Month','$284K','success','fa-check-circle'],
    ];
    @endphp
    @foreach($invStats as [$l,$v,$c,$i])
    <div class="col-md-3">
        <div class="stat-card {{ $c }}">
            <div class="stat-icon {{ $c }}"><i class="fa-solid {{ $i }}"></i></div>
            <div class="stat-content"><div class="stat-value">{{ $v }}</div><div class="stat-label">{{ $l }}</div></div>
        </div>
    </div>
    @endforeach
</div>

<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="{{ route('sales.invoices.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Invoice #, customer..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['draft','sent','paid','overdue','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"
                           placeholder="Date From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"
                           placeholder="Date To">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('sales.invoices.index') }}" class="btn btn-secondary w-100">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Invoices</div>
        <button class="btn btn-secondary btn-sm" onclick="exportInvoices()">
            <i class="fa-solid fa-file-excel"></i> Export
        </button>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th style="text-align:right">Total</th>
                    <th style="text-align:right">Balance Due</th>
                    <th style="width:130px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices ?? collect() as $inv)
                <tr>
                    <td>
                        <a href="{{ route('sales.invoices.show',$inv) }}"
                           style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">
                           {{ $inv->invoice_number }}
                        </a>
                    </td>
                    <td style="font-weight:500">{{ $inv->customer->name }}</td>
                    <td><x-status-badge :status="$inv->status" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $inv->invoice_date->format('M d, Y') }}</td>
                    <td style="font-size:12.5px;color:{{ $inv->due_date && $inv->due_date->isPast() && $inv->status !== 'paid' ? 'var(--danger)' : 'var(--text-muted)' }}">
                        {{ $inv->due_date?->format('M d, Y') ?? '—' }}
                    </td>
                    <td style="text-align:right;font-weight:600;font-family:monospace">${{ number_format($inv->total,2) }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:{{ $inv->balance_due > 0 ? 'var(--danger)' : 'var(--success)' }}">
                        ${{ number_format($inv->balance_due,2) }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('sales.invoices.show',$inv) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fa-solid fa-eye" style="font-size:11px"></i>
                            </a>
                            <a href="{{ route('sales.invoices.edit',$inv) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fa-solid fa-pen" style="font-size:11px"></i>
                            </a>
                            <a href="{{ route('sales.invoices.pdf',$inv) }}" class="btn btn-secondary btn-sm btn-icon" title="PDF" target="_blank">
                                <i class="fa-solid fa-file-pdf" style="font-size:11px;color:var(--danger)"></i>
                            </a>
                            @if(in_array($inv->status,['draft','sent']))
                            <form method="POST" action="{{ route('sales.invoices.send',$inv) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm btn-icon" title="Send to Customer">
                                    <i class="fa-solid fa-paper-plane" style="font-size:11px"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">
                        <i class="fa-solid fa-receipt" style="font-size:32px;opacity:0.3;display:block;margin-bottom:8px"></i>
                        No invoices found.
                        <a href="{{ route('sales.invoices.create') }}" style="color:var(--accent)">Create first invoice →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($invoices) && method_exists($invoices,'hasPages') && $invoices->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
function exportInvoices() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', '1');
    showToast('Preparing export...', 'info');
    setTimeout(() => window.open('?' + params.toString(), '_blank'), 500);
}
</script>
@endpush

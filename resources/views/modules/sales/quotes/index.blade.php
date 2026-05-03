{{-- ============================================================
     resources/views/modules/sales/quotes/index.blade.php
============================================================ --}}
@extends('layouts.app')
@section('title','Quotes')
@section('breadcrumb') Sales / <span class="current">Quotes</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-invoice-dollar" style="color:var(--accent);margin-right:10px"></i>Quotes</h1>
        <p class="page-subtitle">Manage customer quotes and proposals</p>
    </div>
    <a href="{{ route('sales.quotes.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Quote</a>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Quote #, customer..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['draft','sent','won','lost','expired'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i> Filter</button></div>
                <div class="col-md-2"><a href="{{ route('sales.quotes.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Quotes</div>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr><th>Quote #</th><th>Customer</th><th>Status</th><th>Quote Date</th><th>Expiry</th><th style="text-align:right">Total</th><th>Prob.</th><th style="width:120px">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($quotes ?? [] as $quote)
                <tr>
                    <td><a href="{{ route('sales.quotes.show',$quote) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $quote->quote_number }}</a></td>
                    <td style="font-weight:500">{{ $quote->customer->name }}</td>
                    <td><x-status-badge :status="$quote->status" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $quote->quote_date->format('M d, Y') }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $quote->expiry_date?->format('M d, Y') ?? '—' }}</td>
                    <td style="text-align:right;font-weight:600">${{ number_format($quote->total,2) }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px">
                            <div style="height:5px;width:50px;background:#f1f5f9;border-radius:3px;overflow:hidden"><div style="height:100%;width:{{ $quote->probability }}%;background:var(--accent);border-radius:3px"></div></div>
                            <span style="font-size:11.5px;color:var(--text-muted)">{{ $quote->probability }}%</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('sales.quotes.show',$quote) }}" class="btn btn-secondary btn-sm btn-icon" title="View"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('sales.quotes.edit',$quote) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                            <a href="{{ route('sales.quotes.pdf',$quote) }}" class="btn btn-secondary btn-sm btn-icon" title="PDF" target="_blank"><i class="fa-solid fa-file-pdf" style="font-size:11px;color:var(--danger)"></i></a>
                            @if($quote->status === 'draft' || $quote->status === 'sent')
                            <form method="POST" action="{{ route('sales.quotes.convert',$quote) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm btn-icon" title="Convert to Order" onclick="return confirm('Convert this quote to a Sales Order?')">
                                    <i class="fa-solid fa-arrow-right-to-bracket" style="font-size:11px"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">
                    <i class="fa-solid fa-file-invoice-dollar" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px"></i>
                    No quotes found. <a href="{{ route('sales.quotes.create') }}" style="color:var(--accent)">Create your first quote →</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($quotes) && $quotes->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $quotes->links() }}</div>
    @endif
</div>
@endsection

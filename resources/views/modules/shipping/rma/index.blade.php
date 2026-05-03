@extends('layouts.app')
@section('title','RMAs')
@section('breadcrumb') Shipping / <span class="current">RMAs</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-rotate-left" style="color:var(--warning);margin-right:10px"></i>Return Merchandise Authorizations</h1>
    <p class="page-subtitle">Manage customer returns, repairs and replacements</p></div>
    <a href="{{ route('shipping.rma.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New RMA</a>
</div>

<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="RMA #, customer..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['open','received','processing','closed'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        @foreach(['return','repair','replacement','refund'] as $t)
                        <option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('shipping.rma.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--warning)"></i> All RMAs</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>RMA #</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Linked Order</th>
                    <th style="text-align:right">Credit Amount</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rmas ?? collect() as $rma)
                <tr>
                    <td><a href="{{ route('shipping.rma.show',$rma) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $rma->rma_number }}</a></td>
                    <td style="font-weight:500">{{ $rma->customer->name }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($rma->type) }}</span></td>
                    <td><x-status-badge :status="$rma->status" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $rma->rma_date->format('M d, Y') }}</td>
                    <td>
                        @if($rma->order)
                        <a href="{{ route('sales.orders.show',$rma->order) }}" style="color:var(--accent);font-family:monospace;font-size:12px">{{ $rma->order->order_number }}</a>
                        @else<span style="color:var(--text-muted)">—</span>@endif
                    </td>
                    <td style="text-align:right;font-family:monospace;font-weight:600">${{ number_format($rma->credit_amount,2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('shipping.rma.show',$rma) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('shipping.rma.edit',$rma) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                            <form method="POST" action="{{ route('shipping.rma.destroy',$rma) }}" onsubmit="return confirm('Delete RMA?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">
                    No RMAs found. <a href="{{ route('shipping.rma.create') }}" style="color:var(--accent)">Create first RMA →</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($rmas) && method_exists($rmas,'hasPages') && $rmas->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $rmas->links() }}</div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('title','Receiving')
@section('breadcrumb') Purchasing / <span class="current">Receiving</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-boxes-packing" style="color:var(--accent);margin-right:10px"></i>Receiving</h1>
    <p class="page-subtitle">Receive items against purchase orders</p></div>
    <a href="{{ route('purchasing.receiving.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Receipt</a>
</div>
<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET"><div class="row g-2 align-items-end">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Receipt #, PO #, vendor..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending','inspected','stocked']) as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
            <div class="col-md-2"><a href="{{ route('purchasing.receiving.index') }}" class="btn btn-secondary w-100">Clear</a></div>
        </div></form>
    </div>
</div>
<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Receipts</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead><tr><th>Receipt #</th><th>PO #</th><th>Vendor</th><th>Date</th><th>Packing Slip</th><th>Received By</th><th>Status</th><th style="width:100px">Actions</th></tr></thead>
            <tbody>
                @forelse($receipts ?? [] as $r)
                <tr>
                    <td><a href="{{ route('purchasing.receiving.show',$r) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $r->receipt_number }}</a></td>
                    <td><a href="{{ route('purchasing.purchase-orders.show',$r->purchaseOrder) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px">{{ $r->purchaseOrder->po_number }}</a></td>
                    <td style="font-weight:500">{{ $r->vendor->name }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $r->receipt_date->format('M d, Y') }}</td>
                    <td style="font-size:12.5px">{{ $r->packing_slip ?? '—' }}</td>
                    <td style="font-size:12.5px">{{ $r->received_by ?? '—' }}</td>
                    <td><x-status-badge :status="$r->status" /></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('purchasing.receiving.show',$r) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('purchasing.receiving.edit',$r) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">No receipts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($receipts) && $receipts->hasPages())<div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $receipts->links() }}</div>@endif
</div>
@endsection

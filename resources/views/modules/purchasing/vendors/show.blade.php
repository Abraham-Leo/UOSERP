@extends('layouts.app')
@section('title', $vendor->name)
@section('breadcrumb')
    <a href="{{ route('purchasing.vendors.index') }}" style="color:var(--text-muted);text-decoration:none">Purchasing / Vendors</a> /
    <span class="current">{{ $vendor->name }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $vendor->name }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:4px">
            <span class="mono" style="font-size:12px;color:var(--text-muted)">{{ $vendor->vendor_number }}</span>
            @if($vendor->on_hold)<span class="badge badge-danger">On Hold</span>
            @elseif($vendor->is_active)<span class="badge badge-success">Active</span>
            @else<span class="badge badge-secondary">Inactive</span>@endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchasing.purchase-orders.create') }}?vendor_id={{ $vendor->id }}" class="btn btn-secondary"><i class="fa-solid fa-plus"></i> New PO</a>
        <a href="{{ route('purchasing.vendors.edit',$vendor) }}" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Edit</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Contact</div></div>
            <div class="card-body">
                @foreach([
                    ['fa-envelope',$vendor->email ?? '—'],
                    ['fa-phone',$vendor->phone ?? '—'],
                    ['fa-globe',$vendor->website ?? '—'],
                ] as [$icon,$val])
                <div style="display:flex;gap:10px;margin-bottom:10px">
                    <i class="fa-solid {{ $icon }}" style="color:var(--text-muted);width:16px;margin-top:2px"></i>
                    <span style="font-size:13.5px">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Settings</div></div>
            <div class="card-body">
                @foreach([
                    ['Terms',$vendor->payment_terms],
                    ['Currency',$vendor->currency],
                    ['Min Order','$'.number_format($vendor->minimum_order,0)],
                    ['Rating',$vendor->rating.'/5'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><div class="card-title">Purchase Orders</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>PO #</th><th>Status</th><th>Date</th><th style="text-align:right">Total</th><th style="text-align:right">Balance</th><th></th></tr></thead>
                <tbody>
                    @forelse($vendor->purchaseOrders->take(15) as $po)
                    <tr>
                        <td><a href="{{ route('purchasing.purchase-orders.show',$po) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $po->po_number }}</a></td>
                        <td><x-status-badge :status="$po->status" /></td>
                        <td style="font-size:12.5px;color:var(--text-muted)">{{ $po->po_date->format('M d, Y') }}</td>
                        <td style="text-align:right;font-weight:600">${{ number_format($po->total,2) }}</td>
                        <td style="text-align:right;color:{{ $po->balance > 0 ? 'var(--danger)' : 'var(--success)' }}">${{ number_format($po->balance,2) }}</td>
                        <td><a href="{{ route('purchasing.purchase-orders.show',$po) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No purchase orders.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

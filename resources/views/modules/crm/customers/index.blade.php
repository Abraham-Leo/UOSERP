@extends('layouts.app')
@section('title','Customers')
@section('breadcrumb') CRM / <span class="current">Customers</span> @endsection
@section('content')
@php
$stats = $stats ?? [
    'total'   => \App\Models\Customer::count(),
    'active'  => \App\Models\Customer::where('is_active',true)->count(),
    'new_30d' => \App\Models\Customer::where('created_at','>=',now()->subDays(30))->count(),
    'ar_total'=> 0,
];
@endphp
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-users" style="color:var(--accent);margin-right:10px"></i>Customers</h1>
        <p class="page-subtitle">Manage customer accounts, contacts, and history</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary" onclick="window.location='{{ route('crm.customers.index') }}?export=1'">
            <i class="fa-solid fa-file-excel"></i> Export
        </button>
        <a href="{{ route('crm.customers.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Customer
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card accent">
            <div class="stat-icon accent"><i class="fa-solid fa-users"></i></div>
            <div class="stat-content"><div class="stat-value">{{ number_format($stats['total']) }}</div><div class="stat-label">Total Customers</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fa-solid fa-user-check"></i></div>
            <div class="stat-content"><div class="stat-value">{{ number_format($stats['active']) }}</div><div class="stat-label">Active</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fa-solid fa-user-plus"></i></div>
            <div class="stat-content"><div class="stat-value">{{ $stats['new_30d'] }}</div><div class="stat-label">New Last 30 Days</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div class="stat-content"><div class="stat-value">${{ number_format($stats['ar_total'], 0) }}</div><div class="stat-label">Total A/R Balance</div></div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="{{ route('crm.customers.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <div style="position:relative">
                        <i class="fa-solid fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-light);font-size:12px;"></i>
                        <input type="text" name="search" class="form-control" placeholder="Name, number, email..." value="{{ request('search') }}" style="padding-left:32px">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="customer">Customer</option>
                        <option value="prospect">Prospect</option>
                        <option value="lead">Lead</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i> Filter</button></div>
                <div class="col-md-2"><a href="{{ route('crm.customers.index') }}" class="btn btn-secondary w-100"><i class="fa-solid fa-xmark"></i> Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Customers
            <span class="badge badge-primary ms-2">{{ isset($customers) ? $customers->total() : 0 }}</span>
        </div>
        @if(isset($customers) && $customers->total() > 0)
        <div style="font-size:12px;color:var(--text-muted)">
            Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }} of {{ $customers->total() }}
        </div>
        @endif
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Customer #</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Terms</th>
                    <th>Status</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers ?? collect() as $customer)
                <tr>
                    <td>
                        <a href="{{ route('crm.customers.show', $customer) }}" style="color:var(--accent);font-weight:500;font-family:'DM Mono',monospace;font-size:12.5px;">
                            {{ $customer->customer_number }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:500">{{ $customer->name }}</div>
                        @if($customer->company_name && $customer->company_name !== $customer->name)
                            <div style="font-size:11.5px;color:var(--text-muted)">{{ $customer->company_name }}</div>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $customer->email ?? '—' }}</td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $customer->phone ?? '—' }}</td>
                    <td style="font-size:13px">
                        {{ implode(', ', array_filter([$customer->billing_city, $customer->billing_state])) ?: '—' }}
                    </td>
                    <td><span class="badge badge-secondary">{{ $customer->payment_terms }}</span></td>
                    <td>
                        @if($customer->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('crm.customers.show', $customer) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fa-solid fa-eye" style="font-size:11px"></i>
                            </a>
                            <a href="{{ route('crm.customers.edit', $customer) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fa-solid fa-pen" style="font-size:11px"></i>
                            </a>
                            <form method="POST" action="{{ route('crm.customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon" title="Delete">
                                    <i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">
                        <i class="fa-solid fa-users" style="font-size:32px;opacity:0.3;display:block;margin-bottom:8px"></i>
                        No customers found.
                        <a href="{{ route('crm.customers.create') }}" style="color:var(--accent)">Create your first customer →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($customers) && method_exists($customers,'hasPages') && $customers->hasPages())
    <div class="card-body" style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection

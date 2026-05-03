@extends('layouts.app')

@section('title', 'Sales Orders')
@section('breadcrumb')
    Sales / <span class="current">Orders</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-cart-flatbed" style="color:var(--accent);margin-right:10px"></i>Sales Orders</h1>
        <p class="page-subtitle">Manage all customer orders — stock, work orders, and service</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.orders.create') }}?type=stock" class="btn btn-secondary">
            <i class="fa-solid fa-box"></i> Stock Order
        </a>
        <a href="{{ route('sales.orders.create') }}?type=work_order" class="btn btn-secondary">
            <i class="fa-solid fa-gear"></i> Work Order
        </a>
        <a href="{{ route('sales.orders.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Order
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    @php
        $orderStats = [
            ['New', 'primary', 'fa-star', 45],
            ['In Progress', 'warning', 'fa-gear', 82],
            ['Shipped', 'info', 'fa-truck', 28],
            ['Invoiced', 'success', 'fa-check', 194],
            ['Late', 'danger', 'fa-clock', 7],
        ];
    @endphp
    @foreach($orderStats as $s)
    <div class="col">
        <div class="card" style="padding:16px;text-align:center;cursor:pointer" onclick="filterByStatus('{{ strtolower(str_replace(' ','_',$s[0])) }}')">
            <div style="font-size:22px;font-weight:700;color:var(--{{ $s[0] === 'In Progress' ? 'warning' : ($s[0] === 'Invoiced' || $s[0] === 'Shipped' ? 'success' : ($s[0] === 'Late' ? 'danger' : 'accent')) }})">{{ $s[3] }}</div>
            <div style="font-size:11.5px;color:var(--text-muted);margin-top:2px">{{ $s[0] }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search order #, customer..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="invoiced" {{ request('status') === 'invoiced' ? 'selected' : '' }}>Invoiced</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="stock">Stock Order</option>
                        <option value="work_order">Work Order</option>
                        <option value="charge">Charge</option>
                        <option value="build_to_stock">Build to Stock</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="due_from" class="form-control" value="{{ request('due_from') }}" placeholder="Due From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="due_to" class="form-control" value="{{ request('due_to') }}" placeholder="Due To">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Orders</div>
        <div class="d-flex gap-2">
            <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-file-excel"></i> Export</button>
            <button class="btn btn-secondary btn-sm" onclick="printSelected()"><i class="fa-solid fa-print"></i> Print</button>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Due Date</th>
                    <th>Total</th>
                    <th>Released</th>
                    <th style="width:130px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Demo data
                    $demoOrders = [
                        ['SO-2025-0847', 'Acme Industries', 'work_order', 'in_progress', '2025-06-15', '2025-07-15', 48200, true, 'warning'],
                        ['SO-2025-0846', 'TechCorp LLC', 'stock', 'new', '2025-06-18', '2025-07-18', 12400, false, 'primary'],
                        ['SO-2025-0845', 'Global MFG', 'work_order', 'shipped', '2025-06-10', '2025-07-10', 89500, true, 'success'],
                        ['SO-2025-0844', 'Pacific Steel', 'charge', 'invoiced', '2025-06-08', '2025-07-08', 34800, true, 'success'],
                        ['SO-2025-0843', 'Nexus Parts', 'stock', 'new', '2025-07-01', '2025-07-20', 7200, false, 'primary'],
                        ['SO-2025-0842', 'Precision Tools', 'work_order', 'in_progress', '2025-06-20', '2025-07-05', 156800, true, 'warning'],
                        ['SO-2025-0841', 'Delta Systems', 'build_to_stock', 'cancelled', '2025-06-01', '2025-06-30', 28900, false, 'secondary'],
                    ];
                @endphp
                @foreach($demoOrders as $o)
                @php
                    $typeLabels = ['work_order'=>'Work Order','stock'=>'Stock','charge'=>'Charge','build_to_stock'=>'Build to Stock'];
                @endphp
                <tr>
                    <td><input type="checkbox" class="row-select" value="{{ $o[0] }}"></td>
                    <td>
                        <a href="#" style="color:var(--accent);font-weight:600;font-family:'DM Mono',monospace;font-size:12.5px">{{ $o[0] }}</a>
                    </td>
                    <td style="font-weight:500">{{ $o[1] }}</td>
                    <td><span class="badge badge-info" style="font-size:10.5px">{{ $typeLabels[$o[2]] ?? $o[2] }}</span></td>
                    <td><span class="badge badge-{{ $o[8] }}">{{ ucfirst(str_replace('_',' ',$o[3])) }}</span></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $o[4] }}</td>
                    <td style="font-size:12.5px;{{ $o[8] === 'danger' ? 'color:var(--danger);font-weight:600' : 'color:var(--text-muted)' }}">{{ $o[5] }}</td>
                    <td style="font-weight:600">${{ number_format($o[6], 0) }}</td>
                    <td>
                        @if($o[7])
                            <span style="color:var(--success);font-size:12px"><i class="fa-solid fa-check-circle"></i></span>
                        @else
                            <span style="color:var(--text-light);font-size:12px"><i class="fa-regular fa-circle"></i></span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="#" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fa-solid fa-eye" style="font-size:11px"></i>
                            </a>
                            <a href="#" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fa-solid fa-pen" style="font-size:11px"></i>
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm btn-icon" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical" style="font-size:11px"></i>
                                </button>
                                <ul class="dropdown-menu" style="font-size:12.5px;min-width:160px">
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-file-pdf me-2"></i>PDF</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i>Create WO</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-truck me-2"></i>Ship</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-receipt me-2"></i>Invoice</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-ban me-2"></i>Cancel</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-body" style="padding:14px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:12.5px;color:var(--text-muted)">Showing 7 of 356 orders</span>
        <nav>
            <ul class="pagination" style="margin:0;gap:4px">
                @for($i = 1; $i <= 5; $i++)
                <li class="page-item {{ $i === 1 ? 'active' : '' }}">
                    <a class="page-link" href="#" style="font-size:12.5px;border-radius:6px;padding:5px 10px">{{ $i }}</a>
                </li>
                @endfor
                <li class="page-item"><a class="page-link" href="#" style="font-size:12.5px;border-radius:6px;padding:5px 10px">...</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = this.checked);
});

function filterByStatus(status) {
    document.getElementById('statusFilter').value = status;
    document.getElementById('filterForm').submit();
}
</script>
@endpush

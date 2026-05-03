@extends('layouts.app')

@section('title', 'Work Orders')
@section('breadcrumb')
    Production / <span class="current">Work Orders</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-gears" style="color:var(--warning);margin-right:10px"></i>Work Orders</h1>
        <p class="page-subtitle">Track production, manage materials, and monitor shop floor progress</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('production.shop-floor') }}" class="btn btn-secondary">
            <i class="fa-solid fa-industry-windows"></i> Shop Floor View
        </a>
        <a href="{{ route('production.scheduling.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-calendar-days"></i> Schedule
        </a>
        <a href="{{ route('production.work-orders.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Work Order
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fa-solid fa-play"></i></div>
            <div class="stat-content">
                <div class="stat-value">47</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card accent">
            <div class="stat-icon accent"><i class="fa-solid fa-inbox"></i></div>
            <div class="stat-content">
                <div class="stat-value">23</div>
                <div class="stat-label">Queued</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fa-solid fa-fire"></i></div>
            <div class="stat-content">
                <div class="stat-value">8</div>
                <div class="stat-label">Late / Hot</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fa-solid fa-check-double"></i></div>
            <div class="stat-content">
                <div class="stat-value">156</div>
                <div class="stat-label">Completed This Month</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card purple">
            <div class="stat-icon purple"><i class="fa-solid fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value">284</div>
                <div class="stat-label">Labor Hrs This Week</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card info">
            <div class="stat-icon info"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="stat-content">
                <div class="stat-value">12</div>
                <div class="stat-label">Material Shortages</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body" style="padding:12px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="WO#, part, customer..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="open">Open</option>
                        <option value="released">Released</option>
                        <option value="in_progress">In Progress</option>
                        <option value="complete">Complete</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="work_center" class="form-select">
                        <option value="">All Work Centers</option>
                        <option>Assembly</option>
                        <option>Fabrication</option>
                        <option>Testing</option>
                        <option>Shipping Prep</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="filter" class="form-select">
                        <option value="">All Orders</option>
                        <option value="late">Late Only</option>
                        <option value="hot">Hot Orders</option>
                        <option value="shortage">Has Shortage</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('production.mrp.index') }}" class="btn btn-outline-primary w-100">
                        <i class="fa-solid fa-chart-network"></i> Run MRP
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Work Orders Table -->
<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list-check" style="color:var(--warning)"></i> Work Orders</div>
        <div class="d-flex gap-2 align-items-center">
            <span style="font-size:12px;color:var(--text-muted)">
                <i class="fa-solid fa-circle" style="color:var(--success);font-size:8px"></i> = On Track &nbsp;
                <i class="fa-solid fa-circle" style="color:var(--warning);font-size:8px"></i> = At Risk &nbsp;
                <i class="fa-solid fa-circle" style="color:var(--danger);font-size:8px"></i> = Late
            </span>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th style="width:30px"></th>
                    <th>WO Number</th>
                    <th>Part / Product</th>
                    <th>Order</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Progress</th>
                    <th>Assigned To</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $workOrders = [
                        ['WO-2025-0301', 'PCB Assembly X72', 'Rev B', 'SO-2025-0845', 50, 'in_progress', '2025-06-28', '2025-07-12', 72, 'John S.', 'success'],
                        ['WO-2025-0300', 'Wire Harness Kit 4A', 'Rev A', 'SO-2025-0847', 25, 'in_progress', '2025-07-01', '2025-07-15', 38, 'Maria L.', 'warning'],
                        ['WO-2025-0299', 'Motor Controller v3', 'Rev C', 'SO-2025-0844', 10, 'released', '2025-07-05', '2025-07-20', 0, 'Unassigned', 'primary'],
                        ['WO-2025-0298', 'Enclosure Fab Steel', 'Rev A', 'SO-2025-0843', 100, 'open', '2025-07-10', '2025-07-25', 0, 'Bob T.', 'primary'],
                        ['WO-2025-0297', 'Sensor Array Module', 'Rev D', 'SO-2025-0842', 5, 'in_progress', '2025-06-15', '2025-07-05', 90, 'Sarah K.', 'danger'],
                        ['WO-2025-0296', 'Power Supply Unit', 'Rev B', 'SO-2025-0841', 30, 'complete', '2025-06-01', '2025-06-30', 100, 'John S.', 'success'],
                    ];
                @endphp
                @foreach($workOrders as $wo)
                <tr>
                    <td>
                        <div style="width:8px;height:8px;border-radius:50%;background:var(--{{ $wo[10] === 'danger' ? 'danger' : ($wo[10] === 'warning' ? 'warning' : 'success') }});margin:auto"></div>
                    </td>
                    <td>
                        <a href="#" style="color:var(--accent);font-weight:600;font-family:'DM Mono',monospace;font-size:12.5px">{{ $wo[0] }}</a>
                    </td>
                    <td>
                        <div style="font-weight:500;font-size:13.5px">{{ $wo[1] }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $wo[2] }}</div>
                    </td>
                    <td>
                        <a href="#" style="font-size:12.5px;color:var(--accent);font-family:'DM Mono',monospace">{{ $wo[3] }}</a>
                    </td>
                    <td style="font-weight:600">{{ number_format($wo[4]) }}</td>
                    <td>
                        @php
                            $statusColors = ['in_progress'=>'warning','released'=>'primary','open'=>'secondary','complete'=>'success','cancelled'=>'danger'];
                        @endphp
                        <span class="badge badge-{{ $statusColors[$wo[5]] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$wo[5])) }}</span>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $wo[6] }}</td>
                    <td style="font-size:12.5px;{{ $wo[10] === 'danger' ? 'color:var(--danger);font-weight:600' : 'color:var(--text-muted)' }}">
                        {{ $wo[7] }}
                        @if($wo[10] === 'danger')
                            <i class="fa-solid fa-fire" style="color:var(--danger);font-size:10px;margin-left:3px" title="Late!"></i>
                        @endif
                    </td>
                    <td style="min-width:120px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="flex:1;height:6px;background:#f1f5f9;border-radius:3px;overflow:hidden">
                                <div style="height:100%;width:{{ $wo[8] }}%;background:{{ $wo[10] === 'danger' ? 'var(--danger)' : ($wo[10] === 'warning' ? 'var(--warning)' : 'var(--success)') }};border-radius:3px;transition:width 0.5s"></div>
                            </div>
                            <span style="font-size:11px;color:var(--text-muted);font-family:'DM Mono',monospace;width:30px">{{ $wo[8] }}%</span>
                        </div>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $wo[9] }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="#" class="btn btn-secondary btn-sm btn-icon" title="View Work Order">
                                <i class="fa-solid fa-eye" style="font-size:11px"></i>
                            </a>
                            <a href="#" class="btn btn-secondary btn-sm btn-icon" title="Traveler / Router">
                                <i class="fa-solid fa-route" style="font-size:11px"></i>
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm btn-icon" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical" style="font-size:11px"></i>
                                </button>
                                <ul class="dropdown-menu" style="font-size:12.5px;min-width:170px">
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-play me-2" style="color:var(--success)"></i>Clock In</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-clipboard-check me-2" style="color:var(--accent)"></i>Pick Materials</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-check me-2" style="color:var(--success)"></i>Mark Complete</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-magnifying-glass me-2"></i>Inspection</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-print me-2"></i>Print Traveler</a></li>
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
</div>
@endsection

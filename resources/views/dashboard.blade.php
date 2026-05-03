@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb')
    <span class="current">Dashboard</span>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->name }} 👋</h1>
        <p class="page-subtitle">{{ now()->format('l, F j, Y') }} — Here's what's happening today.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.orders.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Order
        </a>
        <a href="{{ route('production.work-orders.create') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-gear"></i> New Work Order
        </a>
    </div>
</div>

<!-- KPI Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card accent">
            <div class="stat-icon accent"><i class="fa-solid fa-dollar-sign"></i></div>
            <div class="stat-content">
                <div class="stat-value">$847K</div>
                <div class="stat-label">Monthly Revenue</div>
                <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> 12.4%</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fa-solid fa-cart-flatbed"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['open_orders'] ?? 184 }}</div>
                <div class="stat-label">Open Orders</div>
                <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> 8.2%</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fa-solid fa-gear"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['work_orders'] ?? 47 }}</div>
                <div class="stat-label">Active Work Orders</div>
                <div class="stat-change down"><i class="fa-solid fa-arrow-down"></i> 3.1%</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['shortages'] ?? 12 }}</div>
                <div class="stat-label">Material Shortages</div>
                <div class="stat-change down"><i class="fa-solid fa-arrow-up"></i> 2</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card purple">
            <div class="stat-icon purple"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div class="stat-content">
                <div class="stat-value">$124K</div>
                <div class="stat-label">A/R Outstanding</div>
                <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> 5.8%</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="stat-card info">
            <div class="stat-icon info"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="stat-content">
                <div class="stat-value">$2.1M</div>
                <div class="stat-label">Inventory Value</div>
                <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> 1.2%</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <!-- Revenue Chart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-chart-line" style="color:var(--accent)"></i> Revenue vs. COGS (Last 12 Months)</div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary btn-sm">Monthly</button>
                    <button class="btn btn-secondary btn-sm">Quarterly</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Order Status Donut -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-circle-half-stroke" style="color:var(--purple)"></i> Order Status</div>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <canvas id="orderDonut" height="200" width="200"></canvas>
                <div class="mt-3" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;width:100%">
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <span style="width:10px;height:10px;border-radius:50%;background:#3b82f6;flex-shrink:0"></span>New Orders
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;flex-shrink:0"></span>In Progress
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <span style="width:10px;height:10px;border-radius:50%;background:#10b981;flex-shrink:0"></span>Completed
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <span style="width:10px;height:10px;border-radius:50%;background:#ef4444;flex-shrink:0"></span>Late / Issues
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row -->
<div class="row g-3">
    <!-- Recent Orders -->
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--accent)"></i> Recent Orders</div>
                <a href="{{ route('sales.orders.index') }}" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div style="overflow-x:auto">
                <table class="erp-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders ?? [] as $order)
                        <tr>
                            <td><a href="{{ route('sales.orders.show', $order) }}" style="color:var(--accent);font-weight:500;font-family:'DM Mono',monospace;font-size:12.5px;">{{ $order->order_number }}</a></td>
                            <td>{{ $order->customer->name }}</td>
                            <td><span class="badge badge-{{ $order->status_color }}">{{ $order->status }}</span></td>
                            <td style="font-weight:600">${{ number_format($order->total, 0) }}</td>
                            <td style="font-size:12px;color:var(--text-muted)">{{ $order->due_date?->format('M d') }}</td>
                        </tr>
                        @endforeach
                        @if(empty($recentOrders) || count($recentOrders) === 0)
                        @php
                            $demoOrders = [
                                ['SO-2025-0847', 'Acme Industries', 'In Progress', 'warning', 48200, 'Jul 15'],
                                ['SO-2025-0846', 'TechCorp LLC', 'New', 'primary', 12400, 'Jul 18'],
                                ['SO-2025-0845', 'Global MFG', 'Shipped', 'success', 89500, 'Jul 10'],
                                ['SO-2025-0844', 'Pacific Steel', 'Late', 'danger', 34800, 'Jul 8'],
                                ['SO-2025-0843', 'Nexus Parts', 'Completed', 'success', 7200, 'Jul 5'],
                            ];
                        @endphp
                        @foreach($demoOrders as $o)
                        <tr>
                            <td><span style="color:var(--accent);font-weight:500;font-family:'DM Mono',monospace;font-size:12.5px;">{{ $o[0] }}</span></td>
                            <td>{{ $o[1] }}</td>
                            <td><span class="badge badge-{{ $o[3] }}">{{ $o[2] }}</span></td>
                            <td style="font-weight:600">${{ number_format($o[4], 0) }}</td>
                            <td style="font-size:12px;color:var(--text-muted)">{{ $o[5] }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Work Order Status -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-industry-windows" style="color:var(--warning)"></i> Shop Floor Status</div>
                <a href="{{ route('production.shop-floor') }}" class="btn btn-secondary btn-sm">Shop Floor</a>
            </div>
            <div class="card-body">
                @php
                    $wos = [
                        ['WO-2025-0301', 'PCB Assembly X72', 'Queue', 'secondary', 75],
                        ['WO-2025-0300', 'Harness Kit 4A', 'Active', 'success', 42],
                        ['WO-2025-0299', 'Motor Controller', 'Active', 'success', 88],
                        ['WO-2025-0298', 'Enclosure Fab', 'On Hold', 'warning', 30],
                        ['WO-2025-0297', 'Sensor Array', 'Late', 'danger', 15],
                    ];
                @endphp
                <div style="display:flex;flex-direction:column;gap:12px">
                    @foreach($wos as $wo)
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:3px;height:36px;border-radius:2px;background:var(--{{ $wo[3] === 'success' ? 'success' : ($wo[3] === 'warning' ? 'warning' : ($wo[3] === 'danger' ? 'danger' : 'text-light')) }});flex-shrink:0"></div>
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                <span style="font-size:12.5px;font-weight:500;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $wo[1] }}</span>
                                <span class="badge badge-{{ $wo[3] }}" style="font-size:10px;flex-shrink:0;margin-left:8px">{{ $wo[2] }}</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="flex:1;height:5px;background:#f1f5f9;border-radius:3px;overflow:hidden">
                                    <div style="height:100%;width:{{ $wo[4] }}%;background:{{ $wo[3] === 'success' ? 'var(--success)' : ($wo[3] === 'warning' ? 'var(--warning)' : ($wo[3] === 'danger' ? 'var(--danger)' : '#cbd5e1')) }};border-radius:3px;transition:width 0.5s"></div>
                                </div>
                                <span style="font-size:11px;color:var(--text-muted);font-family:'DM Mono',monospace">{{ $wo[4] }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts & Actions -->
    <div class="col-xl-3">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-bell" style="color:var(--danger)"></i> Alerts</div>
                <span class="badge badge-danger">5</span>
            </div>
            <div class="card-body" style="padding:12px">
                @php
                    $alerts = [
                        ['danger', 'fa-boxes-stacked', '12 parts below reorder point', 'View MRP'],
                        ['warning', 'fa-clock', '3 purchase orders overdue', 'View POs'],
                        ['warning', 'fa-triangle-exclamation', '2 NCRs awaiting review', 'View NCR'],
                        ['info', 'fa-file-invoice', '8 invoices ready to send', 'View Invoices'],
                        ['success', 'fa-check-circle', '6 POs received today', 'View Receiving'],
                    ];
                @endphp
                <div style="display:flex;flex-direction:column;gap:6px">
                    @foreach($alerts as $a)
                    <div style="display:flex;align-items:flex-start;gap:10px;padding:10px;border-radius:8px;background:var(--bg)">
                        <i class="fa-solid {{ $a[1] }}" style="color:var(--{{ $a[0] }});font-size:13px;margin-top:1px;flex-shrink:0"></i>
                        <div style="flex:1;min-width:0">
                            <div style="font-size:12.5px;color:var(--text)">{{ $a[2] }}</div>
                            <a href="#" style="font-size:11.5px;color:var(--accent);text-decoration:none;font-weight:500">{{ $a[3] }} →</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar','Apr','May','Jun','Jul'],
        datasets: [
            {
                label: 'Revenue',
                data: [620,580,710,850,920,780,690,840,910,780,860,847],
                backgroundColor: 'rgba(59,130,246,0.85)',
                borderRadius: 6,
                borderSkipped: false,
            },
            {
                label: 'COGS',
                data: [420,390,480,580,620,510,460,550,610,530,570,565],
                backgroundColor: 'rgba(16,185,129,0.75)',
                borderRadius: 6,
                borderSkipped: false,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'top', labels: { font: { family: 'DM Sans', size: 12 }, usePointStyle: true } },
            tooltip: { callbacks: { label: ctx => ` $${ctx.raw}K` } }
        },
        scales: {
            y: {
                grid: { color: '#f1f5f9' },
                ticks: { callback: v => '$' + v + 'K', font: { family: 'DM Sans', size: 11 } }
            },
            x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 11 } } }
        }
    }
});

// Order Donut
const ctx2 = document.getElementById('orderDonut').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [45, 82, 38, 19],
            backgroundColor: ['#3b82f6','#f59e0b','#10b981','#ef4444'],
            borderWidth: 2,
            borderColor: '#fff',
            hoverBorderColor: '#fff'
        }]
    },
    options: {
        cutout: '72%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.raw} orders` } }
        }
    }
});
</script>
@endpush

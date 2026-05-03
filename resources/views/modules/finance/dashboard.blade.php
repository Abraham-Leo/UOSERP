@extends('layouts.app')

@section('title', 'Finance Dashboard')
@section('breadcrumb')
    Finance / <span class="current">Overview</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-chart-pie" style="color:var(--success);margin-right:10px"></i>Finance Overview</h1>
        <p class="page-subtitle">General ledger, A/P, A/R, and financial reporting</p>
    </div>
    <div class="d-flex gap-2">
        <select class="form-select" style="width:auto">
            <option>Fiscal Year 2025</option>
            <option>Fiscal Year 2024</option>
        </select>
        <select class="form-select" style="width:auto">
            <option>All Periods</option>
            @for($m = 1; $m <= 12; $m++)
                <option {{ $m == now()->month ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
            @endfor
        </select>
        <a href="{{ route('finance.reports.pl') }}" class="btn btn-primary">
            <i class="fa-solid fa-file-chart-line"></i> P&L Report
        </a>
    </div>
</div>

<!-- Financial KPIs -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fa-solid fa-dollar-sign"></i></div>
            <div class="stat-content">
                <div class="stat-value">$847K</div>
                <div class="stat-label">Revenue (MTD)</div>
                <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> 12.4% vs last month</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fa-solid fa-money-bill-transfer"></i></div>
            <div class="stat-content">
                <div class="stat-value">$565K</div>
                <div class="stat-label">COGS (MTD)</div>
                <div class="stat-change down"><i class="fa-solid fa-arrow-up"></i> 8.2%</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="stat-card accent">
            <div class="stat-icon accent"><i class="fa-solid fa-percent"></i></div>
            <div class="stat-content">
                <div class="stat-value">33.2%</div>
                <div class="stat-label">Gross Margin</div>
                <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> 2.1%</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div class="stat-content">
                <div class="stat-value">$124K</div>
                <div class="stat-label">A/R Outstanding</div>
                <div class="stat-change down"><i class="fa-solid fa-arrow-up"></i> $8K overdue</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="stat-card purple">
            <div class="stat-icon purple"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <div class="stat-content">
                <div class="stat-value">$89K</div>
                <div class="stat-label">A/P Outstanding</div>
                <div class="stat-change up"><i class="fa-solid fa-clock"></i> $22K due this week</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="stat-card info">
            <div class="stat-icon info"><i class="fa-solid fa-piggy-bank"></i></div>
            <div class="stat-content">
                <div class="stat-value">$342K</div>
                <div class="stat-label">Cash Balance</div>
                <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> Healthy</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- P&L Trend -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-chart-area" style="color:var(--success)"></i> Revenue, COGS & Gross Profit (12-Month)</div>
                <a href="{{ route('finance.reports.pl') }}" class="btn btn-secondary btn-sm">Full Report</a>
            </div>
            <div class="card-body">
                <canvas id="plChart" height="90"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-bolt" style="color:var(--warning)"></i> Quick Actions</div>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
                @php
                $actions = [
                    ['Enter Vendor Invoice', 'fa-file-invoice', 'accent', route('finance.accounts-payable.create')],
                    ['Record Customer Payment', 'fa-money-bill-wave', 'success', route('finance.accounts-receivable.index')],
                    ['Bank Reconciliation', 'fa-building-columns', 'purple', route('finance.bank.reconcile')],
                    ['Run A/R Aging Report', 'fa-clock-rotate-left', 'warning', route('finance.reports.ar-aging')],
                    ['Run A/P Aging Report', 'fa-file-chart-line', 'danger', route('finance.reports.ap-aging')],
                    ['View General Ledger', 'fa-book-open', 'info', route('finance.gl')],
                ];
                @endphp
                @foreach($actions as $a)
                <a href="{{ $a[3] }}" class="d-flex align-items-center gap-12 p-3 rounded-2 text-decoration-none"
                   style="gap:12px;padding:12px;border-radius:8px;background:var(--bg);border:1px solid var(--border);transition:all 0.2s"
                   onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="width:36px;height:36px;background:rgba(var(--{{ $a[2] }}-rgb, 59,130,246),0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="fa-solid {{ $a[1] }}" style="color:var(--{{ $a[2] }});font-size:14px"></i>
                    </div>
                    <span style="font-size:13.5px;font-weight:500;color:var(--text)">{{ $a[0] }}</span>
                    <i class="fa-solid fa-chevron-right" style="margin-left:auto;font-size:10px;color:var(--text-muted)"></i>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- A/R Aging -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-hand-holding-dollar" style="color:var(--accent)"></i> A/R Aging Summary</div>
                <a href="{{ route('finance.reports.ar-aging') }}" class="btn btn-secondary btn-sm">Full Report</a>
            </div>
            <div class="card-body">
                @php
                $arAging = [
                    ['Current (0–30)', 72400, 'success'],
                    ['31–60 Days', 28900, 'warning'],
                    ['61–90 Days', 14200, 'orange'],
                    ['91–120 Days', 5800, 'danger'],
                    ['120+ Days', 2700, 'danger'],
                ];
                $total = array_sum(array_column($arAging, 1));
                @endphp
                @foreach($arAging as $bucket)
                @php $pct = round(($bucket[1]/$total)*100); @endphp
                <div style="margin-bottom:14px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                        <span style="font-size:13px;color:var(--text)">{{ $bucket[0] }}</span>
                        <span style="font-size:13px;font-weight:600;color:var(--{{ $bucket[2] === 'orange' ? 'warning' : $bucket[2] }})">
                            ${{ number_format($bucket[1]) }}
                            <span style="font-size:11px;color:var(--text-muted);font-weight:400">({{ $pct }}%)</span>
                        </span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ $pct }}%;background:var(--{{ $bucket[2] === 'orange' ? 'warning' : $bucket[2] }});border-radius:4px;transition:width 0.5s"></div>
                    </div>
                </div>
                @endforeach
                <div style="padding-top:10px;border-top:1px solid var(--border);display:flex;justify-content:space-between;font-weight:600">
                    <span>Total A/R</span>
                    <span style="color:var(--accent)">${{ number_format($total) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- A/P Aging -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-file-invoice-dollar" style="color:var(--purple)"></i> A/P Aging Summary</div>
                <a href="{{ route('finance.reports.ap-aging') }}" class="btn btn-secondary btn-sm">Full Report</a>
            </div>
            <div class="card-body">
                @php
                $apAging = [
                    ['Current (0–30)', 45200, 'success'],
                    ['31–60 Days', 22100, 'warning'],
                    ['61–90 Days', 14800, 'warning'],
                    ['91+ Days', 6900, 'danger'],
                ];
                $apTotal = array_sum(array_column($apAging, 1));
                @endphp
                @foreach($apAging as $bucket)
                @php $pct = round(($bucket[1]/$apTotal)*100); @endphp
                <div style="margin-bottom:14px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                        <span style="font-size:13px;color:var(--text)">{{ $bucket[0] }}</span>
                        <span style="font-size:13px;font-weight:600;color:var(--{{ $bucket[2] }})">
                            ${{ number_format($bucket[1]) }}
                            <span style="font-size:11px;color:var(--text-muted);font-weight:400">({{ $pct }}%)</span>
                        </span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ $pct }}%;background:var(--{{ $bucket[2] }});border-radius:4px;transition:width 0.5s"></div>
                    </div>
                </div>
                @endforeach
                <div style="padding-top:10px;border-top:1px solid var(--border);display:flex;justify-content:space-between;font-weight:600">
                    <span>Total A/P</span>
                    <span style="color:var(--purple)">${{ number_format($apTotal) }}</span>
                </div>

                <!-- Upcoming Payments -->
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                    <div style="font-size:12px;font-weight:600;color:var(--text-muted);margin-bottom:8px;letter-spacing:0.5px;text-transform:uppercase">Due This Week</div>
                    @php
                    $upcoming = [
                        ['Acme Electronics', '$8,400', '2025-07-05'],
                        ['DigiKey Corp', '$12,200', '2025-07-07'],
                        ['MetalMart Inc', '$1,900', '2025-07-08'],
                    ];
                    @endphp
                    @foreach($upcoming as $up)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid #f1f5f9">
                        <span style="font-size:13px;color:var(--text)">{{ $up[0] }}</span>
                        <div style="text-align:right">
                            <div style="font-size:13px;font-weight:600">{{ $up[1] }}</div>
                            <div style="font-size:11px;color:var(--text-muted)">{{ $up[2] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent GL Transactions -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-book-open" style="color:var(--info)"></i> Recent General Ledger Entries</div>
        <a href="{{ route('finance.gl') }}" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Description</th>
                    <th>Account</th>
                    <th style="text-align:right">Debit</th>
                    <th style="text-align:right">Credit</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @php
                $glEntries = [
                    ['2025-07-03', 'INV-2025-0294', 'Customer Invoice — Acme Industries', '4000 · Sales Revenue', '', '$48,200', 'Invoice'],
                    ['2025-07-03', 'INV-2025-0294', 'COGS — PCB Assembly X72 (50 ea)', '5000 · Cost of Goods Sold', '$32,100', '', 'Invoice'],
                    ['2025-07-02', 'PO-2025-0412', 'Vendor Receipt — DigiKey Corp', '1200 · Inventory Asset', '$8,400', '', 'Receipt'],
                    ['2025-07-02', 'PO-2025-0412', 'Accrued A/P Liability', '2100 · Accounts Payable', '', '$8,400', 'Receipt'],
                    ['2025-07-01', 'PAY-2025-0089', 'Customer Payment — TechCorp LLC', '1100 · Accounts Receivable', '', '$12,400', 'Payment'],
                    ['2025-07-01', 'PAY-2025-0089', 'Cash Received', '1000 · Cash — Checking', '$12,400', '', 'Payment'],
                ];
                @endphp
                @foreach($glEntries as $entry)
                <tr>
                    <td style="font-family:'DM Mono',monospace;font-size:12px;color:var(--text-muted)">{{ $entry[0] }}</td>
                    <td><a href="#" style="font-family:'DM Mono',monospace;font-size:12px;color:var(--accent)">{{ $entry[1] }}</a></td>
                    <td style="font-size:13px;max-width:220px">{{ $entry[2] }}</td>
                    <td style="font-family:'DM Mono',monospace;font-size:12px;color:var(--text-muted)">{{ $entry[3] }}</td>
                    <td style="text-align:right;font-weight:600;color:var(--success);font-family:'DM Mono',monospace;font-size:12.5px">{{ $entry[4] }}</td>
                    <td style="text-align:right;font-weight:600;color:var(--danger);font-family:'DM Mono',monospace;font-size:12.5px">{{ $entry[5] }}</td>
                    <td><span class="badge badge-secondary" style="font-size:10px">{{ $entry[6] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('plChart').getContext('2d');
const months = ['Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar','Apr','May','Jun','Jul'];
new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Revenue',
                data: [620,580,710,850,920,780,690,840,910,780,860,847],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                borderWidth: 2,
            },
            {
                label: 'COGS',
                data: [420,390,480,580,620,510,460,550,610,530,570,565],
                borderColor: '#f59e0b',
                backgroundColor: 'transparent',
                tension: 0.4,
                pointRadius: 3,
                borderWidth: 2,
                borderDash: [5,3],
            },
            {
                label: 'Gross Profit',
                data: [200,190,230,270,300,270,230,290,300,250,290,282],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                borderWidth: 2,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { font: { family: 'DM Sans', size: 12 }, usePointStyle: true } },
            tooltip: { callbacks: { label: ctx => ` $${ctx.raw}K` } }
        },
        scales: {
            y: { grid: { color: '#f1f5f9' }, ticks: { callback: v => '$' + v + 'K', font: { family: 'DM Sans', size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 11 } } }
        }
    }
});
</script>
@endpush

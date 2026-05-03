@extends('layouts.app')
@section('title','Profit & Loss Statement')
@section('breadcrumb') Finance / Reports / <span class="current">Profit & Loss</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-chart-line" style="color:var(--success);margin-right:10px"></i>Profit & Loss Statement</h1>
        <p class="page-subtitle">Revenue, cost of goods, and net income analysis</p>
    </div>
    <div class="d-flex gap-2">
        <input type="date" id="dateFrom" class="form-control" style="width:150px" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
        <input type="date" id="dateTo" class="form-control" style="width:150px" value="{{ now()->format('Y-m-d') }}">
        <button class="btn btn-secondary" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
        <button class="btn btn-primary" onclick="exportPL()"><i class="fa-solid fa-file-excel"></i> Export Excel</button>
    </div>
</div>

@php
$plData = [
    'Revenue' => [
        ['Sales Revenue', '4000', 847200],
        ['Service Revenue', '4100', 12400],
        ['Other Income', '4200', 3800],
    ],
    'Cost of Goods Sold' => [
        ['Materials', '5000', 480000],
        ['Direct Labor', '5100', 62000],
        ['Overhead Applied', '5200', 23000],
    ],
    'Operating Expenses' => [
        ['Salaries & Wages', '6000', 124000],
        ['Rent & Facilities', '6100', 18000],
        ['Utilities', '6200', 4200],
        ['Marketing & Sales', '6300', 8500],
        ['Depreciation', '6400', 6300],
        ['Insurance', '6500', 3200],
        ['Office Supplies', '6600', 1800],
    ],
];
$revenue   = array_sum(array_column($plData['Revenue'], 2));
$cogs      = array_sum(array_column($plData['Cost of Goods Sold'], 2));
$grossProfit = $revenue - $cogs;
$opex      = array_sum(array_column($plData['Operating Expenses'], 2));
$netIncome = $grossProfit - $opex;
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-file-invoice-dollar" style="color:var(--success)"></i> Income Statement</div>
                <span style="font-size:12.5px;color:var(--text-muted)">{{ now()->format('F Y') }}</span>
            </div>
            <div class="card-body" id="plReport">
                @foreach($plData as $section => $lines)
                <div style="margin-bottom:20px">
                    <div style="font-size:10.5px;font-weight:700;color:var(--text-muted);letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;padding:8px 0;border-bottom:1px solid var(--border)">
                        {{ $section }}
                    </div>
                    @foreach($lines as [$name,$acct,$amount])
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 12px;border-radius:6px;font-size:13.5px">
                        <span style="color:var(--text-muted)">
                            {{ $name }}
                            <span style="font-size:11px;font-family:monospace;margin-left:6px;opacity:0.6">#{{ $acct }}</span>
                        </span>
                        <span style="font-family:monospace;font-weight:500">${{ number_format($amount) }}</span>
                    </div>
                    @endforeach
                    <div style="display:flex;justify-content:space-between;padding:10px 12px;font-size:14px;font-weight:700;background:var(--bg);border-radius:8px;margin-top:6px">
                        <span>Total {{ $section }}</span>
                        <span style="font-family:monospace">${{ number_format(array_sum(array_column($lines, 2))) }}</span>
                    </div>
                </div>
                @endforeach

                <div style="height:1px;background:var(--border);margin:16px 0"></div>

                {{-- Gross Profit --}}
                <div style="display:flex;justify-content:space-between;padding:12px;font-size:15px;font-weight:700;background:rgba(16,185,129,0.07);border-radius:8px;margin-bottom:8px">
                    <div>
                        <div>Gross Profit</div>
                        <div style="font-size:12px;font-weight:400;color:var(--text-muted)">Revenue − COGS</div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-family:monospace;color:var(--success)">${{ number_format($grossProfit) }}</div>
                        <div style="font-size:12px;color:var(--success)">{{ number_format($grossProfit / $revenue * 100, 1) }}% margin</div>
                    </div>
                </div>

                {{-- Net Income --}}
                <div style="display:flex;justify-content:space-between;padding:14px;font-size:16px;font-weight:700;background:var(--accent-soft);border-radius:10px;border:1px solid rgba(59,130,246,0.2)">
                    <div>
                        <div>Net Income</div>
                        <div style="font-size:12px;font-weight:400;color:var(--text-muted)">Gross Profit − Operating Expenses</div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-family:monospace;color:{{ $netIncome >= 0 ? 'var(--success)' : 'var(--danger)' }};font-size:20px">
                            {{ $netIncome < 0 ? '(' : '' }}${{ number_format(abs($netIncome)) }}{{ $netIncome < 0 ? ')' : '' }}
                        </div>
                        <div style="font-size:12px;color:{{ $netIncome >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                            {{ number_format($netIncome / $revenue * 100, 1) }}% net margin
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-gauge-high" style="color:var(--accent)"></i> Key Metrics</div></div>
            <div class="card-body">
                @foreach([
                    ['Revenue', '$'.number_format($revenue), 'success'],
                    ['COGS', '$'.number_format($cogs), 'warning'],
                    ['Gross Profit', '$'.number_format($grossProfit), 'success'],
                    ['Gross Margin', number_format($grossProfit / $revenue * 100, 1).'%', 'accent'],
                    ['Operating Expenses', '$'.number_format($opex), 'danger'],
                    ['Net Income', '$'.number_format($netIncome), $netIncome >= 0 ? 'success' : 'danger'],
                    ['Net Margin', number_format($netIncome / $revenue * 100, 1).'%', 'accent'],
                    ['COGS %', number_format($cogs / $revenue * 100, 1).'%', 'warning'],
                ] as [$l, $v, $c])
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:13.5px">
                    <span style="color:var(--text-muted)">{{ $l }}</span>
                    <span style="font-family:monospace;font-weight:700;color:var(--{{ $c }})">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-chart-pie" style="color:var(--accent)"></i> Cost Breakdown</div></div>
            <div class="card-body">
                <canvas id="plChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('plChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['COGS','Gross Profit','OpEx'],
        datasets: [{
            data: [{{ $cogs }}, {{ $grossProfit - $opex }}, {{ $opex }}],
            backgroundColor: ['#f59e0b','#10b981','#ef4444'],
            borderWidth: 2,
            borderColor: 'var(--card-bg)'
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { family: 'DM Sans', size: 11 }, usePointStyle: true }
            }
        }
    }
});

function exportPL() {
    showToast('Generating Excel export...', 'info');
    setTimeout(() => {
        // Build CSV
        const rows = [
            ['Profit & Loss Statement', '{{ now()->format("F Y") }}'],
            [],
            ['REVENUE'],
            @foreach($plData['Revenue'] as [$name, $acct, $amount])
            ['{{ $name }}', '{{ $acct }}', '{{ $amount }}'],
            @endforeach
            ['Total Revenue', '', '{{ $revenue }}'],
            [],
            ['COST OF GOODS SOLD'],
            @foreach($plData['Cost of Goods Sold'] as [$name, $acct, $amount])
            ['{{ $name }}', '{{ $acct }}', '{{ $amount }}'],
            @endforeach
            ['Total COGS', '', '{{ $cogs }}'],
            [],
            ['GROSS PROFIT', '', '{{ $grossProfit }}'],
            ['GROSS MARGIN', '', '{{ number_format($grossProfit / $revenue * 100, 1) }}%'],
            [],
            ['OPERATING EXPENSES'],
            @foreach($plData['Operating Expenses'] as [$name, $acct, $amount])
            ['{{ $name }}', '{{ $acct }}', '{{ $amount }}'],
            @endforeach
            ['Total OpEx', '', '{{ $opex }}'],
            [],
            ['NET INCOME', '', '{{ $netIncome }}'],
            ['NET MARGIN', '', '{{ number_format($netIncome / $revenue * 100, 1) }}%'],
        ];
        const csv = rows.map(r => r.map(c => `"${c}"`).join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'profit-loss-{{ now()->format("Y-m") }}.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        showToast('P&L exported successfully', 'success');
    }, 600);
}
</script>
@endpush

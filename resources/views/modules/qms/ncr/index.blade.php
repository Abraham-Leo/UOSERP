@extends('layouts.app')

@section('title', 'Non-Conformance Reports (NCR)')
@section('breadcrumb')
    Quality / <span class="current">NCR</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-triangle-exclamation" style="color:var(--danger);margin-right:10px"></i>Non-Conformance Reports</h1>
        <p class="page-subtitle">Track quality issues, non-conformances, and corrective actions</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('qms.ncr.create') }}" class="btn btn-danger">
            <i class="fa-solid fa-plus"></i> New NCR
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php
    $ncrStats = [
        ['Open', 14, 'danger', 'fa-circle-exclamation'],
        ['Under Review', 6, 'warning', 'fa-magnifying-glass'],
        ['MRB', 3, 'purple', 'fa-people-group'],
        ['Closed This Month', 22, 'success', 'fa-check-circle'],
        ['Avg. Days to Close', 8.4, 'info', 'fa-clock'],
        ['Cost Impact', '$24,800', 'accent', 'fa-dollar-sign'],
    ];
    @endphp
    @foreach($ncrStats as $s)
    <div class="col-md-2">
        <div class="stat-card {{ $s[2] }}">
            <div class="stat-icon {{ $s[2] }}"><i class="fa-solid {{ $s[3] }}"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $s[1] }}</div>
                <div class="stat-label">{{ $s[0] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filter Bar -->
<div class="card mb-3">
    <div class="card-body" style="padding:12px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="NCR#, part, description..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option>Open</option>
                        <option>Review</option>
                        <option>MRB</option>
                        <option>Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="source" class="form-select">
                        <option value="">All Sources</option>
                        <option>Receiving</option>
                        <option>Production</option>
                        <option>Customer</option>
                        <option>Audit</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="disposition" class="form-select">
                        <option value="">All Dispositions</option>
                        <option>Scrap</option>
                        <option>Rework</option>
                        <option>Use As Is</option>
                        <option>Return to Vendor</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- NCR Table -->
<div class="card table-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-list" style="color:var(--danger)"></i> NCR List
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>NCR #</th>
                    <th>Title / Issue</th>
                    <th>Source</th>
                    <th>Part</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Disposition</th>
                    <th>Assigned To</th>
                    <th>Cost Impact</th>
                    <th>Created</th>
                    <th style="width:110px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                $ncrs = [
                    ['NCR-2025-0047', 'Wrong part received — IC chip', 'Receiving', 'COMP-0091', 50, 'open', 'Return to Vendor', 'Sarah K.', 420, '2025-07-03', 'danger'],
                    ['NCR-2025-0046', 'Solder joint failure on PCB', 'Production', 'SUB-0018', 5, 'review', 'Rework', 'John S.', 850, '2025-07-02', 'warning'],
                    ['NCR-2025-0045', 'Dimensional out of spec', 'Production', 'MECH-0033', 20, 'mrb', 'Use As Is', 'Bob T.', 0, '2025-07-01', 'purple'],
                    ['NCR-2025-0044', 'Customer complaint — scratched finish', 'Customer', 'FG-0001', 2, 'open', 'Rework', 'Maria L.', 1200, '2025-06-30', 'danger'],
                    ['NCR-2025-0043', 'Date code mismatch on capacitors', 'Receiving', 'COMP-0042', 500, 'closed', 'Return to Vendor', 'Sarah K.', 60, '2025-06-28', 'success'],
                    ['NCR-2025-0042', 'Contamination in raw material lot', 'Receiving', 'RAW-0012', 10, 'closed', 'Scrap', 'John S.', 840, '2025-06-25', 'success'],
                ];
                @endphp
                @foreach($ncrs as $ncr)
                @php
                $statusColors = ['open'=>'danger','review'=>'warning','mrb'=>'purple','closed'=>'success'];
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('qms.ncr.show', 1) }}" style="color:var(--accent);font-weight:600;font-family:'DM Mono',monospace;font-size:12.5px">{{ $ncr[0] }}</a>
                    </td>
                    <td style="max-width:200px">
                        <div style="font-size:13.5px;font-weight:500">{{ $ncr[1] }}</div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $ncr[2] === 'Receiving' ? 'info' : ($ncr[2] === 'Customer' ? 'warning' : 'secondary') }}" style="font-size:10px">
                            {{ $ncr[2] }}
                        </span>
                    </td>
                    <td style="font-family:'DM Mono',monospace;font-size:12px;color:var(--accent)">{{ $ncr[3] }}</td>
                    <td style="font-family:'DM Mono',monospace;font-size:12.5px;text-align:right">{{ number_format($ncr[4]) }}</td>
                    <td>
                        <span class="badge badge-{{ $statusColors[$ncr[5]] ?? 'secondary' }}">
                            {{ strtoupper($ncr[5]) }}
                        </span>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $ncr[6] }}</td>
                    <td style="font-size:13px">{{ $ncr[7] }}</td>
                    <td style="font-family:'DM Mono',monospace;font-size:12.5px;{{ $ncr[8] > 0 ? 'color:var(--danger);font-weight:600' : 'color:var(--text-muted)' }}">
                        {{ $ncr[8] > 0 ? '$'.number_format($ncr[8]) : '—' }}
                    </td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $ncr[9] }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('qms.ncr.show', 1) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fa-solid fa-eye" style="font-size:11px"></i>
                            </a>
                            <a href="{{ route('qms.ncr.edit', 1) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fa-solid fa-pen" style="font-size:11px"></i>
                            </a>
                            @if($ncr[5] !== 'closed')
                            <button class="btn btn-success btn-sm btn-icon" title="Close NCR" onclick="closeNCR('{{ $ncr[0] }}')">
                                <i class="fa-solid fa-check" style="font-size:11px"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- NCR Trend + Pareto -->
<div class="row g-4 mt-2">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-chart-line" style="color:var(--danger)"></i> NCR Trend (Last 6 Months)</div>
            </div>
            <div class="card-body">
                <canvas id="ncrTrendChart" height="160"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-chart-bar" style="color:var(--warning)"></i> Defect Pareto by Source</div>
            </div>
            <div class="card-body">
                <canvas id="ncrParetoChart" height="160"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// NCR Trend
new Chart(document.getElementById('ncrTrendChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: ['Feb','Mar','Apr','May','Jun','Jul'],
        datasets: [{
            label: 'NCRs Opened',
            data: [18, 22, 15, 28, 19, 14],
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239,68,68,0.08)',
            fill: true, tension: 0.4, pointRadius: 4, borderWidth: 2
        },{
            label: 'NCRs Closed',
            data: [20, 18, 24, 22, 25, 22],
            borderColor: '#10b981',
            backgroundColor: 'transparent',
            tension: 0.4, pointRadius: 4, borderWidth: 2, borderDash: [5,3]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top', labels: { font: { family: 'DM Sans', size: 11 }, usePointStyle: true } } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'DM Sans', size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 11 } } }
        }
    }
});

// NCR Pareto
new Chart(document.getElementById('ncrParetoChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Receiving','Production','Customer','Supplier','Audit'],
        datasets: [{
            label: 'NCR Count',
            data: [42, 28, 14, 10, 6],
            backgroundColor: ['#ef4444','#f59e0b','#8b5cf6','#3b82f6','#10b981'],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'DM Sans', size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 11 } } }
        }
    }
});

function closeNCR(ncrNumber) {
    if (confirm(`Close NCR ${ncrNumber}?`)) {
        showToast(`NCR ${ncrNumber} closed`, 'success');
    }
}
</script>
@endpush

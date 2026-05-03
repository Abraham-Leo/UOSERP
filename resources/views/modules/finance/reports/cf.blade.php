@extends('layouts.app')
@section('title','Cash Flow Statement')
@section('breadcrumb') Finance / Reports / <span class="current">Cash Flow</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-water" style="color:var(--info);margin-right:10px"></i>Statement of Cash Flows</h1>
    <p class="page-subtitle">Cash inflows and outflows for the period</p></div>
    <div class="d-flex gap-2">
        <input type="date" class="form-control" style="width:150px" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
        <input type="date" class="form-control" style="width:150px" value="{{ now()->format('Y-m-d') }}">
        <button class="btn btn-secondary" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
        <button class="btn btn-primary" onclick="exportCF()"><i class="fa-solid fa-file-excel"></i> Export</button>
    </div>
</div>
@php
$sections = [
    ['Operating Activities','success','fa-gear',[
        ['Net Income',282200],
        ['Depreciation & Amortization',6300],
        ['Change in Accounts Receivable',-18400],
        ['Change in Inventory',-42000],
        ['Change in Accounts Payable',12800],
        ['Change in Accrued Liabilities',3200],
    ]],
    ['Investing Activities','warning','fa-chart-line',[
        ['Purchase of Equipment',-48000],
        ['Sale of Assets',0],
        ['Capital Expenditures',-12000],
    ]],
    ['Financing Activities','info','fa-building-columns',[
        ['Loan Repayments',-24000],
        ['Owner Draws',-50000],
        ['Line of Credit Net Change',0],
    ]],
];
@endphp
<div class="row g-4">
    <div class="col-lg-8">
        @php $runningBalance = 342000; @endphp
        @foreach($sections as [$title,$color,$icon,$lines])
        @php $sectionTotal = collect($lines)->sum(fn($l)=>$l[1]); @endphp
        <div class="card mb-4">
            <div class="card-header" style="background:rgba(var(--{{ $color }}-rgb,59,130,246),0.06)">
                <div class="card-title">
                    <i class="fa-solid {{ $icon }}" style="color:var(--{{ $color }})"></i>
                    {{ $title }}
                </div>
                <span style="font-weight:700;font-family:monospace;color:{{ $sectionTotal >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                    {{ $sectionTotal >= 0 ? '+' : '' }}${{ number_format($sectionTotal) }}
                </span>
            </div>
            <div class="card-body" style="padding:0">
                @foreach($lines as [$name,$amount])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 20px;border-bottom:1px solid var(--border)">
                    <span style="font-size:13.5px;color:var(--text-muted)">{{ $name }}</span>
                    <span style="font-family:monospace;font-weight:{{ $amount !== 0 ? '600' : '400' }};color:{{ $amount > 0 ? 'var(--success)' : ($amount < 0 ? 'var(--danger)' : 'var(--text-muted)') }}">
                        {{ $amount !== 0 ? ($amount > 0 ? '+' : '') . '$' . number_format($amount) : '—' }}
                    </span>
                </div>
                @endforeach
                <div style="display:flex;justify-content:space-between;padding:12px 20px;font-weight:700;font-size:14px;background:var(--bg)">
                    <span>Net Cash from {{ $title }}</span>
                    <span style="font-family:monospace;color:{{ $sectionTotal >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                        {{ $sectionTotal >= 0 ? '+' : '' }}${{ number_format($sectionTotal) }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-lg-4">
        <div class="card mb-4" style="position:sticky;top:80px">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-calculator" style="color:var(--accent)"></i> Cash Summary</div></div>
            <div class="card-body">
                @php
                $opNet = 244100; $invNet = -60000; $finNet = -74000;
                $netChange = $opNet + $invNet + $finNet;
                $beginBalance = 232100;
                $endBalance = $beginBalance + $netChange;
                @endphp
                @foreach([
                    ['Beginning Balance','$'.number_format($beginBalance),'text-muted'],
                    ['Operating Activities',($opNet>=0?'+':'').'$'.number_format($opNet),$opNet>=0?'success':'danger'],
                    ['Investing Activities',($invNet>=0?'+':'').'$'.number_format($invNet),$invNet>=0?'success':'danger'],
                    ['Financing Activities',($finNet>=0?'+':'').'$'.number_format($finNet),$finNet>=0?'success':'danger'],
                    ['Net Change in Cash',($netChange>=0?'+':'').'$'.number_format($netChange),$netChange>=0?'success':'danger'],
                    ['Ending Cash Balance','$'.number_format($endBalance),'accent'],
                ] as [$l,$v,$c])
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:13.5px">
                    <span style="color:var(--text-muted)">{{ $l }}</span>
                    <span style="font-family:monospace;font-weight:700;color:var(--{{ $c }})">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title">Cash Flow Chart</div></div>
            <div class="card-body"><canvas id="cfChart" height="200"></canvas></div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('cfChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Operating','Investing','Financing'],
        datasets: [{
            data: [244100, -60000, -74000],
            backgroundColor: ['#10b981','#f59e0b','#3b82f6'],
            borderRadius: 6
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color:'#f1f5f9' }, ticks: { callback: v => '$'+(v/1000).toFixed(0)+'K', font:{family:'DM Sans',size:11} } },
            x: { grid: { display: false }, ticks: { font:{family:'DM Sans',size:11} } }
        }
    }
});
function exportCF() {
    showToast('Generating Excel export...', 'info');
    setTimeout(() => showToast('Export ready — implement via Laravel Excel', 'success'), 1500);
}
</script>
@endpush

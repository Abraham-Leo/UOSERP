@extends('layouts.app')
@section('title','Balance Sheet')
@section('breadcrumb') Finance / Reports / <span class="current">Balance Sheet</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-scale-balanced" style="color:var(--accent);margin-right:10px"></i>Balance Sheet</h1>
    <p class="page-subtitle">Assets, liabilities and equity as of today</p></div>
    <div class="d-flex gap-2">
        <input type="date" class="form-control" style="width:160px" value="{{ now()->format('Y-m-d') }}">
        <button class="btn btn-secondary" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
        <button class="btn btn-primary" onclick="exportReport()"><i class="fa-solid fa-file-excel"></i> Export Excel</button>
    </div>
</div>
@php
$assets = [
    'Current Assets' => [
        ['Cash — Checking','1000',342000],
        ['Cash — Savings','1001',85000],
        ['Accounts Receivable','1100',124000],
        ['Inventory Asset','1200',2100000],
        ['Prepaid Expenses','1300',12400],
    ],
    'Fixed Assets' => [
        ['Equipment','1500',284000],
        ['Less: Accumulated Depreciation','1510',-84000],
        ['Furniture & Fixtures','1520',42000],
    ],
];
$liabilities = [
    'Current Liabilities' => [
        ['Accounts Payable','2000',89000],
        ['Accrued Liabilities','2100',14200],
        ['Sales Tax Payable','2200',8400],
        ['Customer Deposits','2300',22000],
    ],
    'Long-term Liabilities' => [
        ['Equipment Loan','2500',120000],
        ['Line of Credit','2600',0],
    ],
];
$equity = [
    ['Owner Equity','3000',500000],
    ['Retained Earnings','3100',2142800],
    ['Current Year Net Income','3200',282200],
];
$totalAssets = collect($assets)->flatten(1)->sum(fn($r)=>$r[2]);
$totalLiab   = collect($liabilities)->flatten(1)->sum(fn($r)=>$r[2]);
$totalEquity = collect($equity)->sum(fn($r)=>$r[2]);
@endphp
<div class="row g-4">
    <div class="col-lg-6">
        {{-- ASSETS --}}
        <div class="card mb-4">
            <div class="card-header" style="background:rgba(16,185,129,0.08)">
                <div class="card-title" style="color:var(--success)"><i class="fa-solid fa-arrow-up-right-dots"></i> ASSETS</div>
                <span style="font-weight:700;font-family:monospace;color:var(--success)">${{ number_format($totalAssets) }}</span>
            </div>
            <div class="card-body" style="padding:0">
                @foreach($assets as $section => $lines)
                <div style="padding:12px 20px 4px;background:var(--bg)">
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px">{{ $section }}</div>
                </div>
                @foreach($lines as [$name,$acct,$amount])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 20px;border-bottom:1px solid var(--border)">
                    <div>
                        <span style="font-size:13.5px">{{ $name }}</span>
                        <span style="font-size:11px;color:var(--text-muted);margin-left:8px;font-family:monospace">{{ $acct }}</span>
                    </div>
                    <span style="font-family:monospace;font-weight:600;color:{{ $amount < 0 ? 'var(--danger)' : 'inherit' }}">
                        {{ $amount < 0 ? '(' : '' }}${{ number_format(abs($amount)) }}{{ $amount < 0 ? ')' : '' }}
                    </span>
                </div>
                @endforeach
                @endforeach
                <div style="display:flex;justify-content:space-between;padding:14px 20px;background:rgba(16,185,129,0.06);font-weight:700;font-size:15px">
                    <span>Total Assets</span><span style="font-family:monospace;color:var(--success)">${{ number_format($totalAssets) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        {{-- LIABILITIES --}}
        <div class="card mb-4">
            <div class="card-header" style="background:rgba(239,68,68,0.06)">
                <div class="card-title" style="color:var(--danger)"><i class="fa-solid fa-arrow-down-right"></i> LIABILITIES</div>
                <span style="font-weight:700;font-family:monospace;color:var(--danger)">${{ number_format($totalLiab) }}</span>
            </div>
            <div class="card-body" style="padding:0">
                @foreach($liabilities as $section => $lines)
                <div style="padding:12px 20px 4px;background:var(--bg)">
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px">{{ $section }}</div>
                </div>
                @foreach($lines as [$name,$acct,$amount])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 20px;border-bottom:1px solid var(--border)">
                    <div>
                        <span style="font-size:13.5px">{{ $name }}</span>
                        <span style="font-size:11px;color:var(--text-muted);margin-left:8px;font-family:monospace">{{ $acct }}</span>
                    </div>
                    <span style="font-family:monospace;font-weight:600">${{ number_format($amount) }}</span>
                </div>
                @endforeach
                @endforeach
                <div style="display:flex;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--border);font-weight:700">
                    <span>Total Liabilities</span><span style="font-family:monospace;color:var(--danger)">${{ number_format($totalLiab) }}</span>
                </div>
                {{-- EQUITY --}}
                <div style="padding:12px 20px 4px;background:var(--bg)">
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px">EQUITY</div>
                </div>
                @foreach($equity as [$name,$acct,$amount])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 20px;border-bottom:1px solid var(--border)">
                    <div>
                        <span style="font-size:13.5px">{{ $name }}</span>
                        <span style="font-size:11px;color:var(--text-muted);margin-left:8px;font-family:monospace">{{ $acct }}</span>
                    </div>
                    <span style="font-family:monospace;font-weight:600">${{ number_format($amount) }}</span>
                </div>
                @endforeach
                <div style="display:flex;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--border);font-weight:700">
                    <span>Total Equity</span><span style="font-family:monospace;color:var(--accent)">${{ number_format($totalEquity) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:14px 20px;background:rgba(59,130,246,0.06);font-weight:700;font-size:15px">
                    <span>Total Liabilities + Equity</span>
                    <span style="font-family:monospace;color:var(--accent)">${{ number_format($totalLiab + $totalEquity) }}</span>
                </div>
            </div>
        </div>
        {{-- Check --}}
        <div class="alert alert-{{ abs($totalAssets - $totalLiab - $totalEquity) < 1 ? 'success' : 'danger' }}">
            <i class="fa-solid fa-{{ abs($totalAssets - $totalLiab - $totalEquity) < 1 ? 'check-circle' : 'exclamation-circle' }}"></i>
            @if(abs($totalAssets - $totalLiab - $totalEquity) < 1)
                Balance sheet balances ✓
            @else
                Balance sheet is out of balance by ${{ number_format(abs($totalAssets - $totalLiab - $totalEquity)) }}
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function exportReport() {
    showToast('Generating Excel report...', 'info');
    setTimeout(() => showToast('Export feature requires server-side processing (QuestPDF/Laravel Excel)', 'warning'), 1000);
}
</script>
@endpush

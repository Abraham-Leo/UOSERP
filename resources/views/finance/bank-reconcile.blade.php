@extends('layouts.app')
@section('title','Bank Reconciliation')
@section('breadcrumb') Finance / <span class="current">Bank Reconciliation</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-building-columns" style="color:var(--info);margin-right:10px"></i>Bank Reconciliation</h1>
    <p class="page-subtitle">Reconcile bank statements with GL cash accounts</p></div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Reconciliation Setup</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Bank Account</label>
                        <select class="form-select">
                            <option>1000 — Cash — Checking</option>
                            <option>1001 — Cash — Savings</option>
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Statement Date</label>
                        <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}"></div>
                    <div class="col-12"><label class="form-label">Statement Ending Balance ($)</label>
                        <div class="input-group"><span class="input-group-text">$</span>
                        <input type="number" class="form-control" step="0.01" id="endingBalance" oninput="calcRecon()"></div></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title">Reconciliation Summary</div></div>
            <div class="card-body">
                @foreach([
                    ['Beginning Balance','$342,000.00','text-muted'],
                    ['+ Deposits Cleared','$284,200.00','success'],
                    ['− Checks Cleared','$198,400.00','danger'],
                    ['= Cleared Balance','$427,800.00','accent'],
                    ['Statement Balance','—','text-muted'],
                    ['Difference','—','warning'],
                ] as [$l,$v,$c])
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:13.5px">
                    <span style="color:var(--text-muted)">{{ $l }}</span>
                    <span id="recon_{{ Str::slug($l) }}" class="mono" style="font-weight:600;color:var(--{{ $c }})">{{ $v }}</span>
                </div>
                @endforeach
                <div style="margin-top:16px">
                    <button class="btn btn-success w-100"><i class="fa-solid fa-check"></i> Complete Reconciliation</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Transactions to Clear</div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary btn-sm" onclick="document.querySelectorAll('.clear-check').forEach(cb=>cb.checked=true)">Select All</button>
                    <button class="btn btn-secondary btn-sm">Import Statement</button>
                </div>
            </div>
            <div style="overflow-x:auto">
                <table class="erp-table" style="width:100%">
                    <thead><tr><th><input type="checkbox"></th><th>Date</th><th>Type</th><th>Description</th><th style="text-align:right">Amount</th></tr></thead>
                    <tbody>
                        @php $txs = [
                            ['2025-07-03','Deposit','Customer Payment — Acme',48200],
                            ['2025-07-02','Check #1042','Vendor Payment — DigiKey',-8400],
                            ['2025-07-01','Deposit','Customer Payment — TechCorp',12400],
                            ['2025-06-30','ACH','Vendor Payment — MetalMart',-1900],
                            ['2025-06-28','Deposit','Customer Payment — Pacific Steel',34800],
                        ]; @endphp
                        @foreach($txs as $tx)
                        <tr>
                            <td><input type="checkbox" class="clear-check"></td>
                            <td style="font-family:monospace;font-size:12px">{{ $tx[0] }}</td>
                            <td><span class="badge badge-{{ $tx[3] > 0 ? 'success' : 'secondary' }}">{{ $tx[1] }}</span></td>
                            <td style="font-size:13px">{{ $tx[2] }}</td>
                            <td style="text-align:right;font-family:monospace;font-weight:600;color:{{ $tx[3] > 0 ? 'var(--success)' : 'var(--danger)' }}">
                                {{ $tx[3] > 0 ? '+' : '' }}${{ number_format(abs($tx[3])) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

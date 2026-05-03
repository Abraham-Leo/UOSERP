@extends('layouts.app')
@section('title','Bank Reconciliation')
@section('breadcrumb') Finance / <span class="current">Bank Reconciliation</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-building-columns" style="color:var(--info);margin-right:10px"></i>Bank Reconciliation</h1>
    <p class="page-subtitle">Reconcile bank statements with GL cash accounts</p></div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
        <button class="btn btn-success" onclick="completeRecon()"><i class="fa-solid fa-check"></i> Complete Reconciliation</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <!-- Setup Card -->
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-sliders" style="color:var(--accent)"></i> Setup</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Bank Account</label>
                        <select class="form-select" id="bankAccount">
                            <option value="1000">1000 — Cash — Checking</option>
                            <option value="1001">1001 — Cash — Savings</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Statement Date</label>
                        <input type="date" class="form-control datepicker" id="stmtDate" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Beginning Balance ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="beginBalance" value="232100" step="0.01" oninput="calcRecon()">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Statement Ending Balance ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="endingBalance" step="0.01" placeholder="Enter from bank statement..." oninput="calcRecon()">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-calculator" style="color:var(--success)"></i> Reconciliation Summary</div></div>
            <div class="card-body">
                @foreach([
                    ['beginning_balance','Beginning Balance','text-muted'],
                    ['deposits_cleared','+ Deposits Cleared','success'],
                    ['checks_cleared','− Checks Cleared','danger'],
                    ['cleared_balance','= Cleared Balance','accent'],
                    ['statement_balance','Statement Balance','text-muted'],
                    ['difference','Difference','warning'],
                ] as [$id,$label,$color])
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:13.5px">
                    <span style="color:var(--text-muted)">{{ $label }}</span>
                    <span id="recon_{{ $id }}" style="font-family:monospace;font-weight:700;color:var(--{{ $color }})">$0.00</span>
                </div>
                @endforeach
                <div style="margin-top:16px">
                    <div id="reconStatus" style="padding:12px;border-radius:8px;text-align:center;font-weight:600;display:none"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Transactions -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Transactions to Clear</div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary btn-sm" onclick="selectAll()"><i class="fa-solid fa-check-double"></i> Select All</button>
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-file-import"></i> Import Statement</button>
                </div>
            </div>
            <div style="overflow-x:auto">
                <table class="erp-table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="selectAllCb" onchange="selectAll()"></th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Reference</th>
                            <th style="text-align:right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $transactions = [
                            ['2025-07-03','Deposit','Customer Payment — Acme Industries','PAY-0089',48200],
                            ['2025-07-03','Check #1042','Vendor Payment — DigiKey Corp','CHK-1042',-8400],
                            ['2025-07-01','Deposit','Customer Payment — TechCorp LLC','PAY-0088',12400],
                            ['2025-06-30','ACH','Vendor Payment — MetalMart Inc','ACH-0234',-1900],
                            ['2025-06-28','Deposit','Customer Payment — Pacific Steel','PAY-0087',34800],
                            ['2025-06-27','Check #1041','Vendor Payment — Wire Works','CHK-1041',-6200],
                            ['2025-06-25','ACH','Payroll','PAY-PRG',-48000],
                            ['2025-06-24','Deposit','Customer Payment — Nexus Parts','PAY-0086',7200],
                        ];
                        @endphp
                        @foreach($transactions as $tx)
                        <tr>
                            <td><input type="checkbox" class="clear-check" data-amount="{{ $tx[4] }}" onchange="calcRecon()"></td>
                            <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $tx[0] }}</td>
                            <td>
                                <span class="badge badge-{{ $tx[4] > 0 ? 'success' : 'secondary' }}">{{ $tx[1] }}</span>
                            </td>
                            <td style="font-size:13px">{{ $tx[2] }}</td>
                            <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $tx[3] }}</td>
                            <td style="text-align:right;font-family:monospace;font-weight:600;color:{{ $tx[4] > 0 ? 'var(--success)' : 'var(--danger)' }}">
                                {{ $tx[4] > 0 ? '+' : '' }}${{ number_format(abs($tx[4])) }}
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
@push('scripts')
<script>
function calcRecon() {
    const begin = parseFloat(document.getElementById('beginBalance').value) || 0;
    const stmtEnd = parseFloat(document.getElementById('endingBalance').value) || 0;
    let deposits = 0, checks = 0;
    document.querySelectorAll('.clear-check:checked').forEach(cb => {
        const amt = parseFloat(cb.dataset.amount) || 0;
        if (amt > 0) deposits += amt; else checks += Math.abs(amt);
    });
    const cleared = begin + deposits - checks;
    const diff = stmtEnd - cleared;

    document.getElementById('recon_beginning_balance').textContent = '$' + begin.toFixed(2);
    document.getElementById('recon_deposits_cleared').textContent = '+$' + deposits.toFixed(2);
    document.getElementById('recon_checks_cleared').textContent = '−$' + checks.toFixed(2);
    document.getElementById('recon_cleared_balance').textContent = '$' + cleared.toFixed(2);
    document.getElementById('recon_statement_balance').textContent = stmtEnd ? '$' + stmtEnd.toFixed(2) : '—';
    document.getElementById('recon_difference').textContent = stmtEnd ? (diff >= 0 ? '+' : '') + '$' + diff.toFixed(2) : '—';

    const status = document.getElementById('reconStatus');
    if (stmtEnd && Math.abs(diff) < 0.01) {
        status.style.display = 'block';
        status.style.background = 'rgba(16,185,129,0.1)';
        status.style.color = 'var(--success)';
        status.innerHTML = '<i class="fa-solid fa-check-circle"></i> Balanced ✓ Ready to complete';
    } else if (stmtEnd) {
        status.style.display = 'block';
        status.style.background = 'rgba(239,68,68,0.08)';
        status.style.color = 'var(--danger)';
        status.innerHTML = '<i class="fa-solid fa-exclamation-circle"></i> Out of balance by $' + Math.abs(diff).toFixed(2);
    } else {
        status.style.display = 'none';
    }
}
function selectAll() {
    const all = document.getElementById('selectAllCb').checked;
    document.querySelectorAll('.clear-check').forEach(cb => cb.checked = all);
    calcRecon();
}
function completeRecon() {
    const stmtEnd = document.getElementById('endingBalance').value;
    if (!stmtEnd) { showToast('Enter statement ending balance first', 'warning'); return; }
    if (confirm('Complete this reconciliation? This cannot be undone.')) {
        showToast('Reconciliation completed successfully!', 'success');
    }
}
document.addEventListener('DOMContentLoaded', calcRecon);
</script>
@endpush

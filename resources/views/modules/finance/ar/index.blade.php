@extends('layouts.app')
@section('title','Accounts Receivable')
@section('breadcrumb') Finance / <span class="current">Accounts Receivable</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-hand-holding-dollar" style="color:var(--success);margin-right:10px"></i>Accounts Receivable</h1>
        <p class="page-subtitle">Manage customer payments and collections</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
        <i class="fa-solid fa-money-bill-wave"></i> Record Payment
    </button>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Total Outstanding','$124K','accent','fa-hand-holding-dollar'],
        ['Overdue (30+ Days)','$18K','danger','fa-clock-rotate-left'],
        ['Collected This Month','$284K','success','fa-check-circle'],
        ['Credit Memos','$2.4K','warning','fa-file-invoice'],
    ] as [$l,$v,$c,$i])
    <div class="col-md-3">
        <div class="stat-card {{ $c }}">
            <div class="stat-icon {{ $c }}"><i class="fa-solid {{ $i }}"></i></div>
            <div class="stat-content"><div class="stat-value">{{ $v }}</div><div class="stat-label">{{ $l }}</div></div>
        </div>
    </div>
    @endforeach
</div>

<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Invoice #, customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['draft','sent','paid','overdue','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="aging" class="form-select">
                        <option value="">All Aging</option>
                        <option value="0-30">0–30 Days</option>
                        <option value="31-60">31–60 Days</option>
                        <option value="61-90">61–90 Days</option>
                        <option value="90+">90+ Days</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('finance.accounts-receivable.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--success)"></i> Open Receivables</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th>Days Past Due</th>
                    <th style="text-align:right">Total</th>
                    <th style="text-align:right">Paid</th>
                    <th style="text-align:right">Balance Due</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices ?? collect() as $inv)
                @php
                    $daysPast = $inv->due_date ? max(0, now()->diffInDays($inv->due_date, false) * -1) : 0;
                @endphp
                <tr>
                    <td><a href="{{ route('sales.invoices.show',$inv) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $inv->invoice_number }}</a></td>
                    <td style="font-weight:500">{{ $inv->customer->name }}</td>
                    <td><x-status-badge :status="$inv->status" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $inv->invoice_date->format('M d, Y') }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $inv->due_date?->format('M d, Y') ?? '—' }}</td>
                    <td>
                        @if($daysPast > 0)
                            <span class="badge badge-{{ $daysPast > 60 ? 'danger' : 'warning' }}">{{ $daysPast }} days</span>
                        @else
                            <span style="color:var(--success);font-size:12px">Current</span>
                        @endif
                    </td>
                    <td style="text-align:right;font-family:monospace;font-weight:600">${{ number_format($inv->total,2) }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--success)">${{ number_format($inv->amount_paid,2) }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:{{ $inv->balance_due > 0 ? 'var(--danger)' : 'var(--success)' }}">${{ number_format($inv->balance_due,2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('sales.invoices.show',$inv) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            @if($inv->status !== 'paid')
                            <button class="btn btn-success btn-sm btn-icon" title="Record Payment"
                                onclick="openPayment('{{ $inv->id }}','{{ $inv->invoice_number }}','{{ $inv->balance_due }}')">
                                <i class="fa-solid fa-money-bill-wave" style="font-size:11px"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Demo data --}}
                @php
                $demoAR = [
                    ['INV-2025-0294','Acme Industries','sent','2025-06-01','2025-07-01',48200,0,48200,0],
                    ['INV-2025-0293','TechCorp LLC','sent','2025-06-15','2025-07-15',12400,0,12400,0],
                    ['INV-2025-0292','Global MFG','overdue','2025-05-15','2025-06-15',34800,0,34800,32],
                    ['INV-2025-0291','Pacific Steel','paid','2025-06-01','2025-07-01',89500,89500,0,0],
                    ['INV-2025-0290','Nexus Parts','sent','2025-06-20','2025-07-20',7200,0,7200,0],
                    ['INV-2025-0289','Delta Systems','overdue','2025-04-30','2025-05-30',18600,0,18600,57],
                ];
                @endphp
                @foreach($demoAR as $d)
                <tr>
                    <td><span style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $d[0] }}</span></td>
                    <td style="font-weight:500">{{ $d[1] }}</td>
                    <td><x-status-badge :status="$d[2]" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $d[3] }}</td>
                    <td style="font-size:12.5px;color:{{ $d[8] > 0 ? 'var(--danger)' : 'var(--text-muted)' }}">{{ $d[4] }}</td>
                    <td>
                        @if($d[8] > 0)
                            <span class="badge badge-{{ $d[8] > 60 ? 'danger' : 'warning' }}">{{ $d[8] }} days</span>
                        @else
                            <span style="color:var(--success);font-size:12px">Current</span>
                        @endif
                    </td>
                    <td style="text-align:right;font-family:monospace;font-weight:600">${{ number_format($d[5],2) }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--success)">${{ number_format($d[6],2) }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:{{ $d[7] > 0 ? 'var(--danger)' : 'var(--success)' }}">${{ number_format($d[7],2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></button>
                            @if($d[2] !== 'paid')
                            <button class="btn btn-success btn-sm btn-icon" title="Record Payment"
                                onclick="openPayment('{{ $d[0] }}','{{ $d[0] }}','{{ $d[7] }}')">
                                <i class="fa-solid fa-money-bill-wave" style="font-size:11px"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($invoices) && method_exists($invoices,'hasPages') && $invoices->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $invoices->links() }}</div>
    @endif
</div>

{{-- Record Payment Modal --}}
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px">
            <div class="modal-header" style="border-bottom:1px solid var(--border)">
                <h5 class="modal-title" style="font-size:15px;font-weight:600">
                    <i class="fa-solid fa-money-bill-wave" style="color:var(--success);margin-right:8px"></i>Record Customer Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" id="paymentForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Invoice</label>
                            <input type="text" id="pay_inv_num" class="form-control" readonly style="background:var(--bg)">
                            <input type="hidden" name="invoice_id" id="pay_inv_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount ($) <span style="color:var(--danger)">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="amount" id="pay_amount" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Date <span style="color:var(--danger)">*</span></label>
                            <input type="date" name="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                @foreach(['Check','Wire Transfer','ACH','Credit Card','Cash'] as $m)
                                <option>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reference #</label>
                            <input type="text" name="reference_number" class="form-control" placeholder="Check #, Wire #...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border)">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-check"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function openPayment(invId, invNum, balance) {
    document.getElementById('pay_inv_id').value = invId;
    document.getElementById('pay_inv_num').value = invNum;
    document.getElementById('pay_amount').value = parseFloat(balance).toFixed(2);
    // Update form action if real route available
    const form = document.getElementById('paymentForm');
    if (form) form.action = `/finance/accounts-receivable/${invId}/collect`;
    new bootstrap.Modal(document.getElementById('recordPaymentModal')).show();
}
document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
    // For demo, prevent actual submission and show toast
    e.preventDefault();
    bootstrap.Modal.getInstance(document.getElementById('recordPaymentModal'))?.hide();
    showToast('Payment recorded successfully', 'success');
});
</script>
@endpush

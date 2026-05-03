@extends('layouts.app')
@section('title','Accounts Payable')
@section('breadcrumb') Finance / <span class="current">Accounts Payable</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-money-bill-transfer" style="color:var(--purple);margin-right:10px"></i>Accounts Payable</h1>
        <p class="page-subtitle">Manage vendor invoices, vouchers and payments</p>
    </div>
    <a href="{{ route('finance.accounts-payable.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Enter Vendor Invoice
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Total Outstanding','$89K','purple','fa-file-invoice-dollar'],
        ['Due This Week','$22K','danger','fa-calendar-xmark'],
        ['Overdue','$14K','danger','fa-clock'],
        ['Paid This Month','$142K','success','fa-check-circle'],
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
        <form method="GET" action="{{ route('finance.accounts-payable.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Voucher #, vendor..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['pending','approved','paid','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="aging" class="form-select">
                        <option value="">All Aging</option>
                        <option value="current">Current (0-30)</option>
                        <option value="31-60">31–60 Days</option>
                        <option value="61-90">61–90 Days</option>
                        <option value="90+">90+ Days</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('finance.accounts-payable.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--purple)"></i> Vouchers / Vendor Invoices</div>
        <button class="btn btn-primary btn-sm" onclick="paySelected()">
            <i class="fa-solid fa-money-bill"></i> Pay Selected
        </button>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" onchange="document.querySelectorAll('.row-select').forEach(cb=>cb.checked=this.checked)"></th>
                    <th>Voucher #</th>
                    <th>Vendor</th>
                    <th>Vendor Invoice #</th>
                    <th>Status</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th style="text-align:right">Amount</th>
                    <th style="text-align:right">Paid</th>
                    <th style="text-align:right">Balance</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers ?? collect() as $v)
                <tr>
                    <td><input type="checkbox" class="row-select" value="{{ $v->id }}"></td>
                    <td><a href="{{ route('finance.accounts-payable.show',$v) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $v->voucher_number }}</a></td>
                    <td style="font-weight:500">{{ $v->vendor->name }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $v->vendor_invoice_number ?? '—' }}</td>
                    <td><x-status-badge :status="$v->status" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $v->invoice_date->format('M d, Y') }}</td>
                    <td style="font-size:12.5px;color:{{ $v->due_date->isPast() && $v->status !== 'paid' ? 'var(--danger)' : 'var(--text-muted)' }}">{{ $v->due_date->format('M d, Y') }}</td>
                    <td style="text-align:right;font-weight:600;font-family:monospace">${{ number_format($v->amount,2) }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--success)">${{ number_format($v->amount_paid,2) }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:{{ $v->balance > 0 ? 'var(--danger)' : 'var(--success)' }}">${{ number_format($v->balance,2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('finance.accounts-payable.show',$v) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            @if($v->status !== 'paid')
                            <form method="POST" action="{{ route('finance.ap.pay',$v) }}">@csrf
                                <button type="submit" class="btn btn-success btn-sm btn-icon" title="Mark Paid"><i class="fa-solid fa-check" style="font-size:11px"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Demo data --}}
                @php
                $demoAP = [
                    ['VCHR-2025-0089','DigiKey Corp','INV-DK-84291','pending','2025-07-01','2025-07-31',8400,0,8400],
                    ['VCHR-2025-0088','Acme Electronics','INV-AE-29847','approved','2025-06-28','2025-07-28',24600,0,24600],
                    ['VCHR-2025-0087','MetalMart Inc','INV-MM-11234','pending','2025-06-25','2025-07-25',1900,0,1900],
                    ['VCHR-2025-0086','Wire Works LLC','INV-WW-00892','paid','2025-06-20','2025-07-20',6200,6200,0],
                    ['VCHR-2025-0085','Contract Fab Co','INV-CF-55123','approved','2025-06-15','2025-07-15',15000,0,15000],
                    ['VCHR-2025-0084','Mouser Electronics','INV-MO-77421','pending','2025-05-30','2025-06-30',4800,0,4800],
                ];
                @endphp
                @foreach($demoAP as $d)
                <tr>
                    <td><input type="checkbox" class="row-select" value="{{ $d[0] }}"></td>
                    <td><span style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $d[0] }}</span></td>
                    <td style="font-weight:500">{{ $d[1] }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $d[2] }}</td>
                    <td><x-status-badge :status="$d[3]" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $d[4] }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $d[5] }}</td>
                    <td style="text-align:right;font-weight:600;font-family:monospace">${{ number_format($d[6],2) }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--success)">${{ number_format($d[7],2) }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:{{ $d[8] > 0 ? 'var(--danger)' : 'var(--success)' }}">${{ number_format($d[8],2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></button>
                            @if($d[3] !== 'paid')
                            <button class="btn btn-success btn-sm btn-icon" title="Mark Paid"
                                onclick="showToast('Payment recorded for {{ $d[0] }}','success')">
                                <i class="fa-solid fa-check" style="font-size:11px"></i>
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
    @if(isset($vouchers) && method_exists($vouchers,'hasPages') && $vouchers->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $vouchers->links() }}</div>
    @endif
</div>
@endsection
@push('scripts')
<script>
function paySelected() {
    const selected = [...document.querySelectorAll('.row-select:checked')].map(cb => cb.value);
    if (!selected.length) { showToast('Select vouchers to pay first', 'warning'); return; }
    if (confirm(`Pay ${selected.length} voucher(s)?`)) {
        showToast(`Processing payment for ${selected.length} voucher(s)...`, 'info');
        setTimeout(() => showToast('Payments recorded successfully', 'success'), 1500);
    }
}
</script>
@endpush

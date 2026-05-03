@extends('layouts.app')
@section('title','General Ledger')
@section('breadcrumb') Finance / <span class="current">General Ledger</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-book-open" style="color:var(--info);margin-right:10px"></i>General Ledger</h1>
    <p class="page-subtitle">Chart of accounts, transactions and journal entries</p></div>
    <div class="d-flex gap-2">
        <select class="form-select" style="width:auto">
            <option>All Account Types</option>
            <option>Asset</option><option>Liability</option><option>Equity</option>
            <option>Revenue</option><option>Expense</option>
        </select>
        <input type="date" class="form-control" style="width:150px" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
        <input type="date" class="form-control" style="width:150px" value="{{ now()->format('Y-m-d') }}">
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--info)"></i> Chart of Accounts</div>
        <span style="font-size:12.5px;color:var(--text-muted)">Real-time balances</span>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table erp-datatable" style="width:100%">
            <thead><tr><th>Account #</th><th>Account Name</th><th>Type</th><th>Sub-Type</th><th style="text-align:right">Balance</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($accounts ?? [] as $acc)
                <tr>
                    <td style="font-family:monospace;font-size:12.5px;font-weight:600;color:var(--accent)">{{ $acc->account_number }}</td>
                    <td style="font-weight:500">{{ $acc->name }}</td>
                    <td>
                        @php $typeColor = ['asset'=>'success','liability'=>'danger','equity'=>'purple','revenue'=>'accent','expense'=>'warning'][$acc->type] ?? 'secondary'; @endphp
                        <span class="badge badge-{{ $typeColor }}">{{ ucfirst($acc->type) }}</span>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $acc->sub_type ? ucfirst($acc->sub_type) : '—' }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:600;color:{{ $acc->balance >= 0 ? 'var(--success)' : 'var(--danger)' }}">${{ number_format(abs($acc->balance),2) }}</td>
                    <td>@if($acc->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif</td>
                </tr>
                @empty
                @foreach([
                    ['1000','Cash — Checking','asset','current',342000],
                    ['1100','Accounts Receivable','asset','current',124000],
                    ['1200','Inventory Asset','asset','current',2100000],
                    ['2000','Accounts Payable','liability','current',-89000],
                    ['2100','Accrued Liabilities','liability','current',-14000],
                    ['4000','Sales Revenue','revenue',null,847200],
                    ['5000','Cost of Goods Sold','expense','cogs',-565000],
                    ['6000','Salaries & Wages','expense','operating',-124000],
                ] as [$num,$name,$type,$sub,$bal])
                <tr>
                    <td style="font-family:monospace;font-size:12.5px;font-weight:600;color:var(--accent)">{{ $num }}</td>
                    <td style="font-weight:500">{{ $name }}</td>
                    <td><span class="badge badge-{{ ['asset'=>'success','liability'=>'danger','equity'=>'purple','revenue'=>'accent','expense'=>'warning'][$type] ?? 'secondary' }}">{{ ucfirst($type) }}</span></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $sub ? ucfirst($sub) : '—' }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:600;color:{{ $bal >= 0 ? 'var(--success)' : 'var(--danger)' }}">${{ number_format(abs($bal),2) }}</td>
                    <td><span class="badge badge-success">Active</span></td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

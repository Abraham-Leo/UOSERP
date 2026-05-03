@extends('layouts.app')
@section('title','Leads')
@section('breadcrumb') CRM / <span class="current">Leads</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-user-plus" style="color:var(--accent);margin-right:10px"></i>Leads</h1>
        <p class="page-subtitle">Track and convert sales leads</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('crm.pipeline') }}" class="btn btn-secondary"><i class="fa-solid fa-filter-circle-dollar"></i> Pipeline View</a>
        <a href="{{ route('crm.leads.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Lead</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Name, company, email..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['new','contacted','qualified','converted','lost'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('crm.leads.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Leads</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Source</th>
                    <th>Follow-up</th>
                    <th>Assigned</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads ?? collect() as $lead)
                <tr>
                    <td style="font-weight:500">{{ $lead->name }}</td>
                    <td style="font-size:13px;color:var(--text-muted)">{{ $lead->company ?? '—' }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $lead->email ?? '—' }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $lead->phone ?? '—' }}</td>
                    <td><x-status-badge :status="$lead->status" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $lead->source ?? '—' }}</td>
                    <td style="font-size:12.5px;color:{{ $lead->follow_up_date && $lead->follow_up_date->isPast() ? 'var(--danger)' : 'var(--text-muted)' }}">
                        {{ $lead->follow_up_date?->format('M d, Y') ?? '—' }}
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $lead->assignedTo?->name ?? 'Unassigned' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('crm.leads.show',$lead) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('crm.leads.edit',$lead) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                            <form method="POST" action="{{ route('crm.leads.destroy',$lead) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted)">
                    No leads found. <a href="{{ route('crm.leads.create') }}" style="color:var(--accent)">Add first lead →</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($leads) && method_exists($leads,'hasPages') && $leads->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $leads->links() }}</div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('title','Opportunities')
@section('breadcrumb') CRM / <span class="current">Opportunities</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-bullseye-arrow" style="color:var(--accent);margin-right:10px"></i>Opportunities</h1>
        <p class="page-subtitle">Track sales opportunities and pipeline</p>
    </div>
    <a href="{{ route('crm.opportunities.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Opportunity</a>
</div>
<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Opportunities</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Opportunity</th>
                    <th>Status</th>
                    <th style="text-align:right">Est. Value</th>
                    <th>Probability</th>
                    <th>Target Date</th>
                    <th>Assigned</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opportunities ?? collect() as $opp)
                <tr>
                    <td style="font-weight:500">{{ $opp->customer->name ?? '—' }}</td>
                    <td>{{ $opp->title ?? $opp->name ?? '—' }}</td>
                    <td><x-status-badge :status="$opp->status ?? 'open'" /></td>
                    <td style="text-align:right;font-weight:600;font-family:monospace">${{ number_format($opp->value ?? 0, 0) }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px">
                            <div style="height:5px;width:60px;background:#f1f5f9;border-radius:3px;overflow:hidden">
                                <div style="height:100%;width:{{ $opp->probability ?? 0 }}%;background:var(--accent);border-radius:3px"></div>
                            </div>
                            <span style="font-size:11.5px;color:var(--text-muted)">{{ $opp->probability ?? 0 }}%</span>
                        </div>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $opp->target_date?->format('M d, Y') ?? '—' }}</td>
                    <td style="font-size:12.5px">{{ $opp->assignedTo?->name ?? '—' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('crm.opportunities.show',$opp) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('crm.opportunities.edit',$opp) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">
                    No opportunities yet. <a href="{{ route('crm.opportunities.create') }}" style="color:var(--accent)">Create first opportunity →</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

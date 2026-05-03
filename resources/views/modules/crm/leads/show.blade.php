@extends('layouts.app')
@section('title', $lead->name)
@section('breadcrumb')
    <a href="{{ route('crm.leads.index') }}" style="color:var(--text-muted);text-decoration:none">CRM / Leads</a> /
    <span class="current">{{ $lead->name }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $lead->name }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <x-status-badge :status="$lead->status" />
            @if($lead->company)<span style="font-size:13px;color:var(--text-muted)">{{ $lead->company }}</span>@endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('crm.leads.edit',$lead) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
        <a href="{{ route('sales.quotes.create') }}?lead_id={{ $lead->id }}" class="btn btn-primary"><i class="fa-solid fa-file-invoice-dollar"></i> Create Quote</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Lead Details</div></div>
            <div class="card-body">
                @foreach([
                    ['Name', $lead->name],
                    ['Company', $lead->company ?? '—'],
                    ['Email', $lead->email ?? '—'],
                    ['Phone', $lead->phone ?? '—'],
                    ['Status', ucfirst($lead->status)],
                    ['Source', $lead->source ?? '—'],
                    ['Follow-up', $lead->follow_up_date?->format('M d, Y') ?? '—'],
                    ['Assigned To', $lead->assignedTo?->name ?? 'Unassigned'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
                @if($lead->notes)
                <div style="margin-top:12px;font-size:13.5px;color:var(--text-muted);white-space:pre-line">{{ $lead->notes }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

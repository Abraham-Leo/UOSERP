@extends('layouts.app')
@section('title','Sales Pipeline')
@section('breadcrumb') CRM / <span class="current">Sales Pipeline</span> @endsection
@push('styles')
<style>
.pipeline-col { background:var(--bg); border-radius:12px; padding:14px; min-height:400px; }
.pipeline-header { font-size:13px; font-weight:700; margin-bottom:12px; display:flex; justify-content:space-between; align-items:center; }
.pipeline-card { background:var(--card-bg); border:1px solid var(--border); border-radius:10px; padding:14px; margin-bottom:10px; cursor:pointer; transition:all 0.2s; }
.pipeline-card:hover { border-color:var(--accent); box-shadow:var(--shadow); transform:translateY(-2px); }
</style>
@endpush
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-filter-circle-dollar" style="color:var(--accent);margin-right:10px"></i>Sales Pipeline</h1>
    <p class="page-subtitle">Kanban view of leads through the sales process</p></div>
    <div class="d-flex gap-2">
        <a href="{{ route('crm.leads.index') }}" class="btn btn-secondary"><i class="fa-solid fa-list"></i> List View</a>
        <a href="{{ route('crm.leads.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Lead</a>
    </div>
</div>

@php
$stages = [
    'new'       => ['New',       'secondary', 0],
    'contacted' => ['Contacted', 'info',      0],
    'qualified' => ['Qualified', 'accent',    0],
    'converted' => ['Converted', 'success',   0],
    'lost'      => ['Lost',      'danger',    0],
];
$allLeads = $leads ?? collect();
@endphp

<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px">
    @foreach($stages as $status => [$label,$color,$_])
    @php $stageleads = $allLeads->where('status',$status); @endphp
    <div class="pipeline-col">
        <div class="pipeline-header">
            <span>{{ $label }}</span>
            <span class="badge badge-{{ $color }}">{{ $stageleads->count() }}</span>
        </div>
        @forelse($stageleads as $lead)
        <div class="pipeline-card" onclick="window.location='{{ route('crm.leads.show',$lead) }}'">
            <div style="font-weight:600;font-size:13.5px;margin-bottom:4px">{{ $lead->name }}</div>
            @if($lead->company)<div style="font-size:12px;color:var(--text-muted);margin-bottom:6px">{{ $lead->company }}</div>@endif
            <div style="display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:11.5px;color:var(--text-muted)">{{ $lead->source ?? '—' }}</span>
                @if($lead->follow_up_date)
                <span style="font-size:11px;color:{{ $lead->follow_up_date->isPast() ? 'var(--danger)' : 'var(--text-muted)' }}">
                    <i class="fa-solid fa-calendar" style="font-size:10px"></i> {{ $lead->follow_up_date->format('M d') }}
                </span>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:var(--text-muted);font-size:12.5px">No leads</div>
        @endforelse
    </div>
    @endforeach
</div>
@endsection

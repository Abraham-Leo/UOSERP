@extends('layouts.app')
@section('title','Production Scheduling')
@section('breadcrumb') Production / <span class="current">Scheduling</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-calendar-days" style="color:var(--accent);margin-right:10px"></i>Production Scheduling</h1>
    <p class="page-subtitle">View and manage the production schedule across all work centers</p></div>
    <div class="d-flex gap-2">
        <select class="form-select" style="width:auto">
            <option>All Work Centers</option>
            <option>Assembly</option>
            <option>Fabrication</option>
            <option>Testing</option>
            <option>Outsource</option>
        </select>
        <input type="date" class="form-control" style="width:160px" value="{{ now()->format('Y-m-d') }}">
        <a href="{{ route('production.work-orders.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New WO</a>
    </div>
</div>

{{-- Schedule Legend --}}
<div class="card mb-4">
    <div class="card-body" style="padding:12px 20px;display:flex;align-items:center;gap:24px;flex-wrap:wrap">
        @foreach([['success','On Track'],['warning','At Risk'],['danger','Late / Overdue'],['info','Released'],['secondary','Open / Queue']] as [$c,$l])
        <div style="display:flex;align-items:center;gap:7px;font-size:12.5px">
            <div style="width:14px;height:14px;border-radius:3px;background:var(--{{ $c }});opacity:0.8"></div>
            <span style="color:var(--text-muted)">{{ $l }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Schedule Table --}}
<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list-check" style="color:var(--accent)"></i> Work Orders by Priority</div>
        <div style="font-size:12.5px;color:var(--text-muted)">Sorted by Work Start Date</div>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th style="width:8px"></th>
                    <th>WO #</th><th>Part</th><th>Order</th><th style="text-align:right">Qty</th>
                    <th>Work Center</th><th>Work Start</th><th>Due Date</th>
                    <th>Progress</th><th>Assigned</th><th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($workOrders ?? [] as $wo)
                @php
                    $color = $wo->is_late ? 'danger' : ($wo->status === 'in_progress' ? 'success' : ($wo->status === 'released' ? 'info' : 'secondary'));
                @endphp
                <tr>
                    <td style="padding:0;width:4px"><div style="width:4px;height:100%;min-height:40px;background:var(--{{ $color }})"></div></td>
                    <td><a href="{{ route('production.work-orders.show',$wo) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $wo->wo_number }}</a></td>
                    <td>
                        <div style="font-weight:500;font-size:13px">{{ Str::limit($wo->part->description,30) }}</div>
                        <div style="font-family:monospace;font-size:11px;color:var(--text-muted)">{{ $wo->part->part_number }}</div>
                    </td>
                    <td><a href="{{ $wo->order ? route('sales.orders.show',$wo->order) : '#' }}" style="color:var(--accent);font-family:monospace;font-size:12px">{{ $wo->order?->order_number ?? '—' }}</a></td>
                    <td style="text-align:right;font-family:monospace;font-weight:600">{{ number_format($wo->quantity,0) }}</td>
                    <td style="font-size:13px;color:var(--text-muted)">{{ $wo->operations->first()?->work_center ?? 'General' }}</td>
                    <td style="font-size:12.5px;{{ !$wo->work_start_date ? 'color:var(--warning)' : 'color:var(--text-muted)' }}">{{ $wo->work_start_date?->format('M d, Y') ?? 'Not Set' }}</td>
                    <td style="font-size:12.5px;{{ $wo->is_late ? 'color:var(--danger);font-weight:600' : 'color:var(--text-muted)' }}">
                        {{ $wo->due_date?->format('M d, Y') ?? '—' }}
                        @if($wo->is_late)<i class="fa-solid fa-fire" style="color:var(--danger)"></i>@endif
                    </td>
                    <td style="min-width:140px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="flex:1;height:6px;background:#f1f5f9;border-radius:3px;overflow:hidden">
                                <div style="height:100%;width:{{ $wo->progress_pct }}%;background:var(--{{ $color }});border-radius:3px"></div>
                            </div>
                            <span style="font-size:11px;font-family:monospace;color:var(--text-muted)">{{ $wo->progress_pct }}%</span>
                        </div>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $wo->operations->where('status','in_progress')->first()?->assigned_to ?? '—' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('production.work-orders.show',$wo) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('production.traveler',$wo->id) }}" class="btn btn-secondary btn-sm btn-icon" target="_blank"><i class="fa-solid fa-print" style="font-size:11px"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" style="text-align:center;padding:40px;color:var(--text-muted)">No work orders in schedule.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

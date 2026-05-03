@extends('layouts.app')
@section('title', $wo->wo_number)
@section('breadcrumb')
    <a href="{{ route('production.work-orders.index') }}" style="color:var(--text-muted);text-decoration:none">Production / Work Orders</a> /
    <span class="current">{{ $wo->wo_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $wo->wo_number }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <x-status-badge :status="$wo->status" />
            <span style="color:var(--accent);font-family:monospace;font-size:13px">{{ $wo->part->part_number }}</span>
            <span style="font-size:13px;color:var(--text-muted)">— {{ $wo->part->description }}</span>
            @if($wo->is_late)<span class="badge badge-danger"><i class="fa-solid fa-fire"></i> LATE</span>@endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('production.traveler',$wo->id) }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-print"></i> Traveler</a>
        @if(!$wo->released && $wo->status === 'open')
        <form method="POST" action="{{ route('production.work-orders.release',$wo) }}">@csrf
            <button type="submit" class="btn btn-warning"><i class="fa-solid fa-play"></i> Release</button>
        </form>
        @endif
        @if(in_array($wo->status,['released','in_progress']))
        <form method="POST" action="{{ route('production.work-orders.complete',$wo) }}" onsubmit="return confirm('Mark this WO complete?')">@csrf
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Complete</button>
        </form>
        @endif
        <a href="{{ route('production.work-orders.edit',$wo) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
    </div>
</div>

{{-- Progress Bar --}}
<div class="card mb-4" style="overflow:hidden">
    <div style="padding:16px 20px">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="font-size:13.5px;font-weight:600">Production Progress</span>
            <span style="font-size:13.5px;font-weight:700;color:var(--accent)">{{ $wo->progress_pct }}% Complete</span>
        </div>
        <div style="height:12px;background:#f1f5f9;border-radius:6px;overflow:hidden">
            <div style="height:100%;width:{{ $wo->progress_pct }}%;background:{{ $wo->is_late ? 'var(--danger)' : 'var(--accent)' }};border-radius:6px;transition:width 0.5s"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:12px;color:var(--text-muted)">
            <span>{{ number_format($wo->qty_complete,0) }} of {{ number_format($wo->quantity,0) }} {{ $wo->part->unit_of_measure }}</span>
            <span>{{ $wo->qty_scrapped > 0 ? number_format($wo->qty_scrapped,0).' scrapped' : '' }}</span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Operations / Router --}}
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-route" style="color:var(--warning)"></i> Operations / Router</div>
                <form method="POST" action="{{ route('production.work-orders.clock-in',$wo) }}" class="d-flex gap-2">@csrf
                    <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-play"></i> Clock In</button>
                </form>
            </div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>Seq</th><th>Operation</th><th>Work Center</th><th>Status</th><th style="text-align:right">Est. Hrs</th><th style="text-align:right">Actual Hrs</th><th>Assigned</th></tr></thead>
                <tbody>
                    @forelse($wo->operations->sortBy('sequence') as $op)
                    <tr>
                        <td style="font-family:monospace;font-size:12.5px;font-weight:600;color:var(--accent)">{{ $op->sequence }}</td>
                        <td>
                            <div style="font-weight:500">{{ $op->operation_name }}</div>
                            @if($op->work_instructions)<div style="font-size:11.5px;color:var(--text-muted);margin-top:2px">{{ Str::limit($op->work_instructions,60) }}</div>@endif
                        </td>
                        <td style="font-size:13px;color:var(--text-muted)">{{ $op->work_center ?? '—' }}</td>
                        <td><x-status-badge :status="$op->status" /></td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($op->setup_time_est + $op->run_time_est,2) }}</td>
                        <td style="text-align:right;font-family:monospace;color:{{ ($op->setup_time_actual + $op->run_time_actual) > ($op->setup_time_est + $op->run_time_est) ? 'var(--danger)' : 'var(--success)' }}">{{ number_format($op->setup_time_actual + $op->run_time_actual,2) }}</td>
                        <td style="font-size:12.5px;color:var(--text-muted)">{{ $op->assigned_to ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">No operations defined.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Materials / Pick List --}}
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-clipboard-list" style="color:var(--accent)"></i> Pick List / Materials</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>Part #</th><th>Description</th><th style="text-align:right">Required</th><th style="text-align:right">Picked</th><th style="text-align:right">Consumed</th><th>Lot</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($wo->materials as $mat)
                    <tr>
                        <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">{{ $mat->part->part_number }}</td>
                        <td>{{ $mat->part->description }}</td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($mat->qty_required,2) }}</td>
                        <td style="text-align:right;font-family:monospace;color:{{ $mat->qty_picked >= $mat->qty_required ? 'var(--success)' : 'var(--warning)' }}">{{ number_format($mat->qty_picked,2) }}</td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($mat->qty_consumed,2) }}</td>
                        <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $mat->lot_number ?? '—' }}</td>
                        <td><x-status-badge :status="$mat->status" /></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">No materials assigned.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Labor Entries --}}
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-clock" style="color:var(--purple)"></i> Labor Entries</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>User</th><th>Clock In</th><th>Clock Out</th><th style="text-align:right">Hours</th><th style="text-align:right">OT Hours</th><th style="text-align:right">Labor Cost</th></tr></thead>
                <tbody>
                    @forelse($wo->laborEntries as $entry)
                    <tr>
                        <td style="font-weight:500">{{ $entry->user->name }}</td>
                        <td style="font-family:monospace;font-size:12px">{{ $entry->clock_in->format('M d H:i') }}</td>
                        <td style="font-family:monospace;font-size:12px">{{ $entry->clock_out?->format('M d H:i') ?? '— Active —' }}</td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($entry->hours,2) }}</td>
                        <td style="text-align:right;font-family:monospace;color:var(--warning)">{{ $entry->overtime_hours > 0 ? number_format($entry->overtime_hours,2) : '—' }}</td>
                        <td style="text-align:right;font-family:monospace;font-weight:600">${{ number_format($entry->labor_cost,2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No labor entries yet.</td></tr>
                    @endforelse
                </tbody>
                @if($wo->laborEntries->count())
                <tfoot>
                    <tr style="background:var(--accent-soft)">
                        <td colspan="3" style="text-align:right;font-weight:700;padding:10px 14px">Totals</td>
                        <td style="text-align:right;font-weight:700;font-family:monospace;padding:10px 14px">{{ number_format($wo->labor_hrs_actual,2) }}</td>
                        <td style="text-align:right;padding:10px 14px">—</td>
                        <td style="text-align:right;font-weight:700;font-family:monospace;color:var(--accent);padding:10px 14px">${{ number_format($wo->labor_cost_actual,2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Work Order Info</div></div>
            <div class="card-body">
                @foreach([
                    ['WO #',$wo->wo_number],
                    ['Part',$wo->part->part_number],
                    ['Type',ucfirst($wo->type)],
                    ['Quantity',number_format($wo->quantity,0).' '.$wo->part->unit_of_measure],
                    ['Completed',number_format($wo->qty_complete,0)],
                    ['Scrapped',$wo->qty_scrapped > 0 ? number_format($wo->qty_scrapped,0) : '—'],
                    ['Order Date',$wo->order_date->format('M d, Y')],
                    ['Work Start',$wo->work_start_date?->format('M d, Y') ?? '—'],
                    ['Due Date',$wo->due_date?->format('M d, Y') ?? '—'],
                    ['Linked Order',$wo->order?->order_number ?? '—'],
                    ['Released',$wo->released ? '✅ Yes' : '❌ No'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-dollar-sign" style="color:var(--success)"></i> Cost Summary</div></div>
            <div class="card-body">
                @foreach([
                    ['Material Cost','$'.number_format($wo->material_cost_actual,2)],
                    ['Labor Cost','$'.number_format($wo->labor_cost_actual,2)],
                    ['Overhead','$'.number_format($wo->overhead_cost_actual,2)],
                    ['Outsource','$'.number_format($wo->outsource_cost_actual,2)],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:600;font-family:monospace">{{ $v }}</span>
                </div>
                @endforeach
                <div style="display:flex;justify-content:space-between;padding:10px 0;font-size:15px;font-weight:700">
                    <span>Total Actual</span>
                    <span style="color:var(--accent);font-family:monospace">${{ number_format($wo->material_cost_actual + $wo->labor_cost_actual + $wo->overhead_cost_actual + $wo->outsource_cost_actual,2) }}</span>
                </div>
            </div>
        </div>

        @if($wo->notes)
        <div class="card">
            <div class="card-header"><div class="card-title">Notes</div></div>
            <div class="card-body" style="font-size:13.5px;color:var(--text-muted);white-space:pre-line">{{ $wo->notes }}</div>
        </div>
        @endif
    </div>
</div>
@endsection

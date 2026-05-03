@extends('layouts.app')
@section('title', 'BOM: '.$bom->parentPart->part_number)
@section('breadcrumb')
    <a href="{{ route('inventory.boms.index') }}" style="color:var(--text-muted);text-decoration:none">Inventory / BOMs</a> /
    <span class="current">{{ $bom->parentPart->part_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">BOM: {{ $bom->parentPart->description }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <span class="mono" style="color:var(--accent);font-size:13px">{{ $bom->parentPart->part_number }}</span>
            <span class="badge badge-secondary">Rev {{ $bom->revision }}</span>
            <x-status-badge :status="$bom->status" />
            @if($bom->is_current)<span class="badge badge-success">Current</span>@endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('inventory.boms.edit',$bom) }}" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Edit BOM</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-layer-group" style="color:var(--accent)"></i> Components ({{ $bom->lines->count() }} items)</div>
                <span style="font-size:12.5px;color:var(--text-muted)">Est. Material Cost: <strong style="color:var(--accent)">${{ number_format($bom->lines->sum(fn($l) => $l->quantity * $l->part->standard_cost), 2) }}</strong></span>
            </div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>#</th><th>Part #</th><th>Description</th><th style="text-align:right">Qty</th><th>UOM</th><th style="text-align:right">Std Cost</th><th style="text-align:right">Ext Cost</th><th>Notes</th></tr></thead>
                <tbody>
                    @foreach($bom->lines->sortBy('sort_order') as $i => $line)
                    <tr>
                        <td style="color:var(--text-muted);font-size:12px">{{ $i+1 }}</td>
                        <td><a href="{{ route('inventory.parts.show',$line->part) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px;font-weight:600">{{ $line->part->part_number }}</a></td>
                        <td>{{ $line->part->description }}
                            @if($line->is_phantom)<span class="badge badge-warning ms-1" style="font-size:10px">Phantom</span>@endif</td>
                        <td style="text-align:right;font-family:monospace;font-weight:600">{{ number_format($line->quantity,4) }}</td>
                        <td style="color:var(--text-muted)">{{ $line->unit_of_measure }}</td>
                        <td style="text-align:right;font-family:monospace">${{ number_format($line->part->standard_cost,4) }}</td>
                        <td style="text-align:right;font-family:monospace;font-weight:600">${{ number_format($line->quantity * $line->part->standard_cost,4) }}</td>
                        <td style="font-size:12px;color:var(--text-muted)">{{ Str::limit($line->notes,30) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($bom->operations->count())
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-route" style="color:var(--warning)"></i> Router / Operations</div></div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>Seq</th><th>Operation</th><th>Work Center</th><th style="text-align:right">Setup Hrs</th><th style="text-align:right">Run Hrs</th><th>Outsource</th></tr></thead>
                <tbody>
                    @foreach($bom->operations->sortBy('sequence') as $op)
                    <tr>
                        <td style="font-family:monospace;font-size:12.5px;font-weight:600;color:var(--accent)">{{ $op->sequence }}</td>
                        <td style="font-weight:500">{{ $op->operation_name }}</td>
                        <td style="font-size:13px;color:var(--text-muted)">{{ $op->work_center ?? '—' }}</td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($op->setup_time_hrs,2) }}</td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($op->run_time_hrs,2) }}</td>
                        <td>@if($op->outsource)<span class="badge badge-warning">Yes — {{ $op->outsource_vendor }}</span>@else<span style="color:var(--text-muted);font-size:13px">No</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">BOM Summary</div></div>
            <div class="card-body">
                @foreach([
                    ['Part #',$bom->parentPart->part_number],
                    ['Revision','Rev '.$bom->revision],
                    ['Status',ucfirst($bom->status)],
                    ['Components',$bom->lines->count()],
                    ['Operations',$bom->operations->count()],
                    ['Labor Est.',number_format($bom->labor_estimate_hours,2).' hrs'],
                    ['Effective',$bom->effective_date?->format('M d, Y') ?? '—'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

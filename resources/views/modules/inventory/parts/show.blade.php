@extends('layouts.app')
@section('title', $part->part_number)
@section('breadcrumb')
    <a href="{{ route('inventory.parts.index') }}" style="color:var(--text-muted);text-decoration:none">Inventory / Parts</a> /
    <span class="current">{{ $part->part_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $part->description }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <span class="mono" style="color:var(--accent);font-size:13px;font-weight:600">{{ $part->part_number }}</span>
            <span class="badge badge-{{ $part->type === 'finished_good' ? 'success' : ($part->type === 'subassembly' ? 'purple' : 'primary') }}">{{ ucfirst(str_replace('_',' ',$part->type)) }}</span>
            <span class="badge badge-{{ $part->make_buy === 'buy' ? 'info' : 'purple' }}">{{ strtoupper($part->make_buy) }}</span>
            @if(!$part->is_active)<span class="badge badge-secondary">Inactive</span>@endif
        </div>
    </div>
    <div class="d-flex gap-2">
        @if($part->boms->count())
        <a href="{{ route('inventory.boms.show',$part->boms->first()) }}" class="btn btn-secondary"><i class="fa-solid fa-sitemap"></i> View BOM</a>
        @endif
        <a href="{{ route('inventory.parts.edit',$part) }}" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Edit</a>
    </div>
</div>

<div class="row g-3 mb-4">
    @php
    $qoh = $part->inventory->sum('qty_on_hand');
    $qr  = $part->inventory->sum('qty_reserved');
    $qoo = $part->inventory->sum('qty_on_order');
    $qa  = $qoh - $qr;
    @endphp
    @foreach([
        ['QOH',number_format($qoh,0),$qoh > $part->reorder_point ? 'success' : 'danger','fa-cubes'],
        ['Reserved',number_format($qr,0),'warning','fa-lock'],
        ['On Order',number_format($qoo,0),'info','fa-shopping-cart'],
        ['Available',number_format($qa,0),$qa >= 0 ? 'success' : 'danger','fa-check-circle'],
    ] as [$l,$v,$c,$i])
    <div class="col-md-3">
        <div class="stat-card {{ $c }}">
            <div class="stat-icon {{ $c }}"><i class="fa-solid {{ $i }}"></i></div>
            <div class="stat-content"><div class="stat-value">{{ $v }}</div><div class="stat-label">{{ $l }}</div></div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Part Details</div></div>
            <div class="card-body">
                @foreach([
                    ['Part #',$part->part_number],
                    ['UOM',$part->unit_of_measure],
                    ['Category',$part->category ?? '—'],
                    ['Revision',$part->revision ?? '—'],
                    ['Lead Time',$part->lead_time_days.' days'],
                    ['Reorder Point',number_format($part->reorder_point,0)],
                    ['EOQ',number_format($part->economic_order_qty,0)],
                    ['Std Cost','$'.number_format($part->standard_cost,4)],
                    ['Avg Cost','$'.number_format($part->average_cost,4)],
                    ['Last Cost','$'.number_format($part->last_cost,4)],
                    ['Unit Price','$'.number_format($part->unit_price,4)],
                    ['Track Serial',$part->track_serial ? 'Yes' : 'No'],
                    ['Track Lot',$part->track_lot ? 'Yes' : 'No'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @if($part->notes)
        <div class="card">
            <div class="card-header"><div class="card-title">Notes</div></div>
            <div class="card-body" style="font-size:13.5px;color:var(--text-muted);white-space:pre-line">{{ $part->notes }}</div>
        </div>
        @endif
    </div>
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-warehouse" style="color:var(--accent)"></i> Stock by Location</div>
                <a href="{{ route('inventory.stock.index') }}?part_id={{ $part->id }}" class="btn btn-secondary btn-sm">Adjust Stock</a>
            </div>
            <table class="erp-table" style="width:100%">
                <thead><tr><th>Warehouse</th><th>Bin</th><th style="text-align:right">On Hand</th><th style="text-align:right">Reserved</th><th style="text-align:right">Available</th><th style="text-align:right">Unit Cost</th></tr></thead>
                <tbody>
                    @forelse($part->inventory as $inv)
                    <tr>
                        <td style="font-weight:500">{{ $inv->warehouse->name }}</td>
                        <td style="font-family:monospace;font-size:12px">{{ $inv->binLocation->code ?? 'Default' }}</td>
                        <td style="text-align:right;font-weight:600;font-family:monospace">{{ number_format($inv->qty_on_hand,2) }}</td>
                        <td style="text-align:right;color:var(--warning);font-family:monospace">{{ number_format($inv->qty_reserved,2) }}</td>
                        <td style="text-align:right;font-weight:700;color:{{ $inv->qty_available >= 0 ? 'var(--success)' : 'var(--danger)' }};font-family:monospace">{{ number_format($inv->qty_available,2) }}</td>
                        <td style="text-align:right;font-family:monospace">${{ number_format($inv->unit_cost,4) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No inventory records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <ul class="nav nav-tabs" style="padding:0 16px;border-bottom:1px solid var(--border)">
                <li class="nav-item"><a class="nav-link active" href="#bom" data-bs-toggle="tab">BOM</a></li>
                <li class="nav-item"><a class="nav-link" href="#history" data-bs-toggle="tab">Transaction History</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="bom">
                    @if($part->boms->count())
                    @php $bom = $part->boms->where('is_current',true)->first() ?? $part->boms->first(); @endphp
                    <div style="padding:12px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:13px;color:var(--text-muted)">Rev {{ $bom->revision }} · {{ $bom->lines->count() }} components</span>
                        <a href="{{ route('inventory.boms.show',$bom) }}" class="btn btn-secondary btn-sm">View Full BOM</a>
                    </div>
                    <table class="erp-table" style="width:100%">
                        <thead><tr><th>#</th><th>Part #</th><th>Description</th><th style="text-align:right">Qty / EA</th><th>UOM</th></tr></thead>
                        <tbody>
                            @foreach($bom->lines->take(10) as $i => $line)
                            <tr>
                                <td style="color:var(--text-muted);font-size:12px">{{ $i+1 }}</td>
                                <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">{{ $line->part->part_number }}</td>
                                <td>{{ $line->part->description }}</td>
                                <td style="text-align:right;font-family:monospace">{{ number_format($line->quantity,4) }}</td>
                                <td style="color:var(--text-muted)">{{ $line->unit_of_measure }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div style="text-align:center;padding:32px;color:var(--text-muted)">
                        No BOM. <a href="{{ route('inventory.boms.create') }}?part_id={{ $part->id }}" style="color:var(--accent)">Create BOM →</a>
                    </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="history">
                    <a href="{{ route('inventory.parts.history',$part) }}" class="btn btn-secondary btn-sm" style="margin:12px 16px">View Full History</a>
                    <p style="padding:0 16px 16px;font-size:13.5px;color:var(--text-muted)">Click above to see all transactions for this part.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', $ncr->ncr_number)
@section('breadcrumb')
    <a href="{{ route('qms.ncr.index') }}" style="color:var(--text-muted);text-decoration:none">Quality / NCR</a> /
    <span class="current">{{ $ncr->ncr_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $ncr->ncr_number }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <x-status-badge :status="$ncr->status" />
            <span class="badge badge-{{ $ncr->source === 'receiving' ? 'info' : ($ncr->source === 'customer' ? 'warning' : 'secondary') }}">{{ ucfirst($ncr->source) }}</span>
            @if($ncr->cost_impact > 0)<span class="badge badge-danger"><i class="fa-solid fa-dollar-sign"></i> ${{ number_format($ncr->cost_impact,0) }} impact</span>@endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('qms.ncr.edit',$ncr) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
        @if($ncr->status === 'open')
        <form method="POST" action="{{ route('qms.ncr.escalate',$ncr) }}">@csrf
            <button type="submit" class="btn btn-warning"><i class="fa-solid fa-arrow-up"></i> Escalate to MRB</button>
        </form>
        @endif
        @if($ncr->status !== 'closed')
        <form method="POST" action="{{ route('qms.ncr.close',$ncr) }}" onsubmit="return confirm('Close this NCR?')">@csrf
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Close NCR</button>
        </form>
        @endif
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-triangle-exclamation" style="color:var(--danger)"></i> Issue Details</div></div>
            <div class="card-body">
                <h5 style="font-size:16px;font-weight:600;margin-bottom:12px">{{ $ncr->title }}</h5>
                <p style="font-size:13.5px;color:var(--text-muted);line-height:1.8;margin-bottom:16px">{{ $ncr->description }}</p>
                @if($ncr->containment_area)
                <div class="alert alert-warning">
                    <i class="fa-solid fa-shield-halved"></i>
                    <strong>Containment Area:</strong> {{ $ncr->containment_area }}
                </div>
                @endif
                @if($ncr->resolution)
                <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);border-radius:8px;padding:14px;margin-top:12px">
                    <div style="font-weight:600;color:var(--success);margin-bottom:6px"><i class="fa-solid fa-check-circle"></i> Resolution</div>
                    <p style="font-size:13.5px;color:var(--text-muted);margin:0">{{ $ncr->resolution }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">NCR Details</div></div>
            <div class="card-body">
                @foreach([
                    ['NCR #',$ncr->ncr_number],
                    ['Title',Str::limit($ncr->title,30)],
                    ['Status',ucfirst($ncr->status)],
                    ['Source',ucfirst($ncr->source)],
                    ['Part',$ncr->part?->part_number ?? '—'],
                    ['Qty Non-Conforming',number_format($ncr->quantity,0)],
                    ['Disposition',$ncr->disposition ? ucfirst(str_replace('_',' ',$ncr->disposition)) : '—'],
                    ['Cost Impact','$'.number_format($ncr->cost_impact,2)],
                    ['Assigned To',$ncr->assignedTo?->name ?? '—'],
                    ['Due Date',$ncr->due_date?->format('M d, Y') ?? '—'],
                    ['Closed',$ncr->closed_at?->format('M d, Y') ?? '—'],
                    ['Vendor',$ncr->vendor?->name ?? '—'],
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

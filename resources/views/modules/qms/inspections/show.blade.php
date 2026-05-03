@extends('layouts.app')
@section('title','Inspection Detail')
@section('breadcrumb')
    <a href="{{ route('qms.inspections.index') }}" style="color:var(--text-muted);text-decoration:none">Quality / Inspections</a> /
    <span class="current">Inspection Detail</span>
@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Inspection Record</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('qms.inspections.edit',$inspection) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
        @if(($inspection->result ?? 'pending') === 'fail')
        <a href="{{ route('qms.ncr.create') }}?inspection_id={{ $inspection->id }}" class="btn btn-danger">
            <i class="fa-solid fa-triangle-exclamation"></i> Create NCR
        </a>
        @endif
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title">Inspection Summary</div></div>
            <div class="card-body">
                @foreach([
                    ['Type', ucfirst(str_replace('_',' ',$inspection->type??'incoming'))],
                    ['Part', $inspection->part->part_number ?? '—'],
                    ['Qty Inspected', number_format($inspection->qty_inspected??0,0)],
                    ['Qty Accepted', number_format($inspection->qty_accepted??0,0)],
                    ['Qty Rejected', number_format($inspection->qty_rejected??0,0)],
                    ['Inspector', $inspection->inspector??'—'],
                    ['Date', $inspection->inspection_date?->format('M d, Y') ?? '—'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
                <div style="margin-top:14px;text-align:center">
                    @php $result = $inspection->result ?? 'pending'; @endphp
                    <div style="font-size:28px;font-weight:700;color:var(--{{ $result==='pass'?'success':($result==='fail'?'danger':'warning') }})">
                        {{ strtoupper($result) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><div class="card-title">Notes / Findings</div></div>
            <div class="card-body" style="font-size:13.5px;color:var(--text-muted);white-space:pre-line;line-height:1.8">
                {{ $inspection->notes ?? 'No notes recorded.' }}
            </div>
        </div>
    </div>
</div>
@endsection

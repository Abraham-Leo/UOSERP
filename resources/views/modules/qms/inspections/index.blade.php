@extends('layouts.app')
@section('title','Inspections')
@section('breadcrumb') Quality / <span class="current">Inspections</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-magnifying-glass-chart" style="color:var(--accent);margin-right:10px"></i>Inspections</h1>
        <p class="page-subtitle">Incoming, in-process and final inspections</p>
    </div>
    <a href="{{ route('qms.inspections.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Inspection</a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Open','6','warning','fa-clock'],
        ['Passed Today','14','success','fa-check-circle'],
        ['Failed Today','2','danger','fa-xmark-circle'],
        ['Pending Review','4','info','fa-eye'],
    ] as [$l,$v,$c,$i])
    <div class="col-md-3">
        <div class="stat-card {{ $c }}">
            <div class="stat-icon {{ $c }}"><i class="fa-solid {{ $i }}"></i></div>
            <div class="stat-content"><div class="stat-value">{{ $v }}</div><div class="stat-label">{{ $l }}</div></div>
        </div>
    </div>
    @endforeach
</div>

<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Inspection #, part, order..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="incoming">Incoming / Receiving</option>
                        <option value="in_process">In-Process</option>
                        <option value="final">Final</option>
                        <option value="first_article">First Article</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="result" class="form-select">
                        <option value="">All Results</option>
                        <option value="pass">Pass</option>
                        <option value="fail">Fail</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('qms.inspections.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Inspections</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Inspection #</th>
                    <th>Type</th>
                    <th>Part / Reference</th>
                    <th>Qty Inspected</th>
                    <th>Qty Accepted</th>
                    <th>Qty Rejected</th>
                    <th>Result</th>
                    <th>Inspector</th>
                    <th>Date</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inspections ?? collect() as $insp)
                <tr>
                    <td><a href="{{ route('qms.inspections.show',$insp) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $insp->inspection_number ?? 'INSP-0001' }}</a></td>
                    <td><span class="badge badge-info" style="font-size:10px">{{ ucfirst(str_replace('_',' ',$insp->type ?? 'incoming')) }}</span></td>
                    <td style="font-weight:500">{{ $insp->part->part_number ?? '—' }}</td>
                    <td style="font-family:monospace;text-align:right">{{ number_format($insp->qty_inspected ?? 0,0) }}</td>
                    <td style="font-family:monospace;text-align:right;color:var(--success)">{{ number_format($insp->qty_accepted ?? 0,0) }}</td>
                    <td style="font-family:monospace;text-align:right;color:{{ ($insp->qty_rejected ?? 0) > 0 ? 'var(--danger)' : 'var(--text-muted)' }}">{{ number_format($insp->qty_rejected ?? 0,0) }}</td>
                    <td>
                        @php $result = $insp->result ?? 'pending'; @endphp
                        <span class="badge badge-{{ $result === 'pass' ? 'success' : ($result === 'fail' ? 'danger' : 'warning') }}">
                            {{ ucfirst($result) }}
                        </span>
                    </td>
                    <td style="font-size:12.5px">{{ $insp->inspector ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $insp->created_at?->format('M d, Y') ?? '—' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('qms.inspections.show',$insp) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('qms.inspections.edit',$insp) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Demo rows --}}
                @php
                $demo = [
                    ['INSP-2025-047','incoming','COMP-0042','Capacitor 100uF',200,198,2,'fail','Sarah K.','2025-07-03'],
                    ['INSP-2025-046','in_process','SUB-0018','Control Board',10,10,0,'pass','John S.','2025-07-02'],
                    ['INSP-2025-045','final','FG-0001','Motor Controller',5,5,0,'pass','Maria L.','2025-07-01'],
                    ['INSP-2025-044','incoming','RAW-0012','Steel Sheet',50,50,0,'pass','Bob T.','2025-06-30'],
                    ['INSP-2025-043','first_article','FG-0002','PCB Assembly X72',1,1,0,'pass','Sarah K.','2025-06-28'],
                ];
                @endphp
                @foreach($demo as $d)
                <tr>
                    <td><span style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $d[0] }}</span></td>
                    <td><span class="badge badge-info" style="font-size:10px">{{ ucfirst(str_replace('_',' ',$d[1])) }}</span></td>
                    <td>
                        <div style="font-family:monospace;font-size:12px;color:var(--accent)">{{ $d[2] }}</div>
                        <div style="font-size:13px;font-weight:500">{{ $d[3] }}</div>
                    </td>
                    <td style="font-family:monospace;text-align:right">{{ $d[4] }}</td>
                    <td style="font-family:monospace;text-align:right;color:var(--success)">{{ $d[5] }}</td>
                    <td style="font-family:monospace;text-align:right;color:{{ $d[6] > 0 ? 'var(--danger)' : 'var(--text-muted)' }}">{{ $d[6] > 0 ? $d[6] : '—' }}</td>
                    <td><span class="badge badge-{{ $d[7] === 'pass' ? 'success' : 'danger' }}">{{ ucfirst($d[7]) }}</span></td>
                    <td style="font-size:12.5px">{{ $d[8] }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $d[9] }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></button>
                            <button class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($inspections) && method_exists($inspections,'hasPages') && $inspections->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $inspections->links() }}</div>
    @endif
</div>
@endsection

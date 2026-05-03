@extends('layouts.app')
@section('title','ECO / ECR')
@section('breadcrumb') Quality / <span class="current">ECO / ECR</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-code-branch" style="color:var(--accent);margin-right:10px"></i>Engineering Change Orders</h1>
    <p class="page-subtitle">Manage engineering change orders and requests</p></div>
    <a href="{{ route('qms.eco.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New ECO</a>
</div>
<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET"><div class="row g-2 align-items-end">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="ECO #, title, part..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['draft','review','approved','closed'] as $s)
                    <option {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="eco" {{ request('type')==='eco'?'selected':'' }}>ECO</option>
                    <option value="ecr" {{ request('type')==='ecr'?'selected':'' }}>ECR</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
        </div></form>
    </div>
</div>
<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> ECO / ECR List</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead><tr><th>ECO #</th><th>Type</th><th>Title</th><th>Part</th><th>Rev Change</th><th>Status</th><th>Due Date</th><th>Cost Impact</th><th style="width:100px">Actions</th></tr></thead>
            <tbody>
                @forelse($ecos ?? [] as $eco)
                <tr>
                    <td><a href="{{ route('qms.eco.show',$eco) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $eco->eco_number }}</a></td>
                    <td><span class="badge badge-{{ $eco->type === 'eco' ? 'accent' : 'info' }}">{{ strtoupper($eco->type) }}</span></td>
                    <td style="font-weight:500;max-width:200px">{{ Str::limit($eco->title,40) }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--accent)">{{ $eco->part?->part_number ?? '—' }}</td>
                    <td style="font-family:monospace;font-size:12.5px">
                        @if($eco->rev_from && $eco->rev_to)
                        <span style="color:var(--text-muted)">{{ $eco->rev_from }}</span>
                        <i class="fa-solid fa-arrow-right" style="font-size:10px;margin:0 4px;color:var(--accent)"></i>
                        <span style="color:var(--accent)">{{ $eco->rev_to }}</span>
                        @else—@endif
                    </td>
                    <td><x-status-badge :status="$eco->status" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $eco->due_date?->format('M d, Y') ?? '—' }}</td>
                    <td style="font-family:monospace;font-size:12.5px;color:{{ $eco->cost_impact > 0 ? 'var(--warning)' : 'var(--text-muted)' }}">${{ number_format($eco->cost_impact,2) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('qms.eco.show',$eco) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('qms.eco.edit',$eco) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                            @if($eco->status !== 'approved')
                            <form method="POST" action="{{ route('qms.eco.approve',$eco) }}">@csrf
                                <button type="submit" class="btn btn-success btn-sm btn-icon" title="Approve"><i class="fa-solid fa-check" style="font-size:11px"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted)">No ECOs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

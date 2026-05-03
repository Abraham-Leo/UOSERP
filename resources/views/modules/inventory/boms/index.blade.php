@extends('layouts.app')
@section('title','BOMs')
@section('breadcrumb') Inventory / <span class="current">Bills of Materials</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-sitemap" style="color:var(--accent);margin-right:10px"></i>Bills of Materials</h1>
    <p class="page-subtitle">Manage product structures, routers and work instructions</p></div>
    <a href="{{ route('inventory.boms.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New BOM</a>
</div>
<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET"><div class="row g-2 align-items-end">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Part #, description..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['active','inactive','draft'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
            <div class="col-md-2"><a href="{{ route('inventory.boms.index') }}" class="btn btn-secondary w-100">Clear</a></div>
        </div></form>
    </div>
</div>
<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All BOMs</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead><tr><th>Part #</th><th>Description</th><th>Rev</th><th>Status</th><th># Components</th><th>Labor Est.</th><th>Current</th><th style="width:100px">Actions</th></tr></thead>
            <tbody>
                @forelse($boms ?? [] as $bom)
                <tr>
                    <td><a href="{{ route('inventory.boms.show',$bom) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $bom->parentPart->part_number }}</a></td>
                    <td style="font-weight:500">{{ $bom->parentPart->description }}</td>
                    <td><span class="badge badge-secondary">Rev {{ $bom->revision }}</span></td>
                    <td><x-status-badge :status="$bom->status" /></td>
                    <td>{{ $bom->lines->count() }}</td>
                    <td style="font-family:monospace;font-size:12.5px">{{ number_format($bom->labor_estimate_hours,2) }} hrs</td>
                    <td>@if($bom->is_current)<i class="fa-solid fa-check-circle" style="color:var(--success)"></i>@endif</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('inventory.boms.show',$bom) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('inventory.boms.edit',$bom) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                            <form method="POST" action="{{ route('inventory.boms.destroy',$bom) }}" onsubmit="return confirm('Delete this BOM?')">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">No BOMs found. <a href="{{ route('inventory.boms.create') }}" style="color:var(--accent)">Create first BOM →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($boms) && $boms->hasPages())<div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $boms->links() }}</div>@endif
</div>
@endsection

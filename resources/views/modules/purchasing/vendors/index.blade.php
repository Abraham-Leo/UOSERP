@extends('layouts.app')
@section('title','Vendors')
@section('breadcrumb') Purchasing / <span class="current">Vendors</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-store" style="color:var(--accent);margin-right:10px"></i>Vendors</h1><p class="page-subtitle">Manage supplier accounts and performance</p></div>
    <a href="{{ route('purchasing.vendors.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Vendor</a>
</div>
<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Vendor name, number..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                        <option value="hold" {{ request('status')==='hold'?'selected':'' }}>On Hold</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('purchasing.vendors.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>
<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Vendors</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead><tr><th>Vendor #</th><th>Name</th><th>Email</th><th>Phone</th><th>Terms</th><th>Rating</th><th>Status</th><th style="width:100px">Actions</th></tr></thead>
            <tbody>
                @forelse($vendors ?? [] as $v)
                <tr>
                    <td><a href="{{ route('purchasing.vendors.show',$v) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $v->vendor_number }}</a></td>
                    <td style="font-weight:500">{{ $v->name }}</td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $v->email ?? '—' }}</td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $v->phone ?? '—' }}</td>
                    <td><span class="badge badge-secondary">{{ $v->payment_terms }}</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:4px">
                            @for($i=1;$i<=5;$i++)
                            <i class="fa-{{ $i <= $v->rating ? 'solid' : 'regular' }} fa-star" style="font-size:11px;color:{{ $i <= $v->rating ? 'var(--warning)' : 'var(--border)' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td>
                        @if($v->on_hold)<span class="badge badge-danger">On Hold</span>
                        @elseif($v->is_active)<span class="badge badge-success">Active</span>
                        @else<span class="badge badge-secondary">Inactive</span>@endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('purchasing.vendors.show',$v) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('purchasing.vendors.edit',$v) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                            <form method="POST" action="{{ route('purchasing.vendors.destroy',$v) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">No vendors found. <a href="{{ route('purchasing.vendors.create') }}" style="color:var(--accent)">Add first vendor →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($vendors) && $vendors->hasPages())<div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $vendors->links() }}</div>@endif
</div>
@endsection

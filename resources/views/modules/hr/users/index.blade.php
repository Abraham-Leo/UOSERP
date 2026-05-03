@extends('layouts.app')
@section('title','User Management')
@section('breadcrumb') Admin / <span class="current">Users</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-user-gear" style="color:var(--accent);margin-right:10px"></i>User Management</h1>
    <p class="page-subtitle">Manage system users, roles and permissions</p></div>
    <a href="{{ route('hr.users.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New User</a>
</div>
<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Users</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table erp-datatable" style="width:100%">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Department</th><th>Status</th><th>Last Login</th><th style="width:120px">Actions</th></tr></thead>
            <tbody>
                @forelse($users ?? [] as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:500;font-size:13.5px">{{ $user->name }}</div>
                                @if($user->title)<div style="font-size:11.5px;color:var(--text-muted)">{{ $user->title }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px">{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                        <span class="badge badge-accent">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </td>
                    <td style="font-size:13px;color:var(--text-muted)">{{ $user->department ?? '—' }}</td>
                    <td>
                        @if($user->is_active)<span class="badge badge-success">Active</span>
                        @else<span class="badge badge-secondary">Inactive</span>@endif
                        @if($user->shop_floor_only)<span class="badge badge-info ms-1" style="font-size:10px">Shop Floor</span>@endif
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $user->last_login?->format('M d, Y H:i') ?? 'Never' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('hr.users.show',$user) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('hr.users.edit',$user) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('hr.users.destroy',$user) }}" onsubmit="return confirm('Delete user?')">@csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($users) && $users->hasPages())<div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $users->links() }}</div>@endif
</div>
@endsection

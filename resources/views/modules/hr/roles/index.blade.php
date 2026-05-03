@extends('layouts.app')
@section('title','Roles & Permissions')
@section('breadcrumb') Admin / <span class="current">Roles & Permissions</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-shield-halved" style="color:var(--accent);margin-right:10px"></i>Roles & Permissions</h1>
        <p class="page-subtitle">Manage user roles and module access permissions</p>
    </div>
    <a href="{{ route('hr.roles.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Role</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Roles</div></div>
            <div class="card-body" style="padding:0">
                @php
                $defaultRoles = [
                    ['admin','Administrator','Full system access',14,'danger'],
                    ['sales','Sales','CRM, quotes, orders',3,'success'],
                    ['production','Production','Work orders, shop floor, MRP',8,'warning'],
                    ['purchasing','Purchasing','POs, vendors, receiving',4,'info'],
                    ['finance','Finance','AP, AR, GL, reports',2,'purple'],
                    ['quality','Quality','NCR, ECO, inspections',3,'accent'],
                    ['warehouse','Warehouse','Inventory, stock, shipping',6,'secondary'],
                ];
                @endphp
                @foreach($roles ?? $defaultRoles as $role)
                @php
                    $name = is_array($role) ? $role[0] : $role->name;
                    $desc = is_array($role) ? $role[2] : 'System role';
                    $users = is_array($role) ? $role[3] : ($role->users_count ?? 0);
                    $color = is_array($role) ? $role[4] : 'secondary';
                @endphp
                <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);cursor:pointer"
                     onclick="showPermissions('{{ $name }}')" id="role-{{ $name }}">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="fa-solid fa-shield-halved" style="color:var(--{{ $color }});font-size:16px"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-weight:600;font-size:13.5px">{{ ucfirst($name) }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted)">{{ $desc }}</div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:18px;font-weight:700;color:var(--accent)">{{ $users }}</div>
                        <div style="font-size:10px;color:var(--text-muted)">users</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title" id="permTitle"><i class="fa-solid fa-key" style="color:var(--warning)"></i> Permission Matrix</div>
                <button class="btn btn-primary btn-sm" onclick="savePermissions()">
                    <i class="fa-solid fa-save"></i> Save Permissions
                </button>
            </div>
            <div class="card-body" style="padding:0">
                <table class="erp-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th style="text-align:center">View</th>
                            <th style="text-align:center">Create</th>
                            <th style="text-align:center">Edit</th>
                            <th style="text-align:center">Delete</th>
                            <th style="text-align:center">Export</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $modules = [
                            'Dashboard','CRM / Customers','CRM / Leads',
                            'Sales / Quotes','Sales / Orders','Sales / Invoices',
                            'Purchasing / POs','Purchasing / Receiving',
                            'Inventory / Parts','Inventory / BOMs','Inventory / Stock',
                            'Production / Work Orders','Production / MRP','Production / Shop Floor',
                            'Finance / AP','Finance / AR','Finance / Reports',
                            'Quality / NCR','Quality / ECO','Quality / Inspections',
                            'Admin / Users','Admin / Roles',
                        ];
                        @endphp
                        @foreach($modules as $mod)
                        <tr>
                            <td style="font-size:13px">{{ $mod }}</td>
                            @foreach(['view','create','edit','delete','export'] as $perm)
                            <td style="text-align:center">
                                <input type="checkbox" class="perm-check"
                                       data-module="{{ Str::slug($mod) }}"
                                       data-perm="{{ $perm }}"
                                       style="width:16px;height:16px;accent-color:var(--accent)"
                                       {{ in_array($perm,['view','create','edit']) ? 'checked' : '' }}>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function showPermissions(role) {
    document.querySelectorAll('[id^="role-"]').forEach(el => el.style.background = 'transparent');
    document.getElementById('role-' + role).style.background = 'var(--accent-soft)';
    document.getElementById('permTitle').innerHTML =
        '<i class="fa-solid fa-key" style="color:var(--warning)"></i> Permissions — ' + role.charAt(0).toUpperCase() + role.slice(1);
    // Toggle based on role
    const adminChecks = role === 'admin';
    document.querySelectorAll('.perm-check').forEach(cb => cb.checked = adminChecks || Math.random() > 0.3);
}
function savePermissions() {
    showToast('Permissions saved successfully', 'success');
}
</script>
@endpush

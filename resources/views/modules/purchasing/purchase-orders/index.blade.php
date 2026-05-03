@extends('layouts.app')

@section('title', 'Purchase Orders')
@section('breadcrumb')
    Purchasing / <span class="current">Purchase Orders</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-circle-plus" style="color:var(--accent);margin-right:10px"></i>Purchase Orders</h1>
        <p class="page-subtitle">Manage vendor purchase orders, receiving, and AP</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchasing.purchase-orders.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New PO
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php
    $poStats = [
        ['Open POs', 34, 'accent', 'fa-folder-open'],
        ['Pending Ack.', 8, 'warning', 'fa-hourglass-half'],
        ['Partial Received', 12, 'info', 'fa-boxes-packing'],
        ['Overdue', 5, 'danger', 'fa-calendar-xmark'],
        ['Closed This Month', 47, 'success', 'fa-check-double'],
        ['Total PO Value', '$284K', 'purple', 'fa-dollar-sign'],
    ];
    @endphp
    @foreach($poStats as $s)
    <div class="col-md-2">
        <div class="stat-card {{ $s[2] }}">
            <div class="stat-icon {{ $s[2] }}"><i class="fa-solid {{ $s[3] }}"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $s[1] }}</div>
                <div class="stat-label">{{ $s[0] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body" style="padding:12px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="PO#, vendor..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option>Draft</option>
                        <option>Sent</option>
                        <option>Acknowledged</option>
                        <option>Partial</option>
                        <option>Received</option>
                        <option>Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option>Standard</option>
                        <option>Outsource</option>
                        <option>Internal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="vendor" class="form-control" placeholder="Vendor name...">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- PO Table -->
<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Purchase Orders</div>
        <div class="d-flex gap-2">
            <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-file-excel"></i> Export</button>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>PO Number</th>
                    <th>Vendor</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>PO Date</th>
                    <th>Commit Date</th>
                    <th style="text-align:right">PO Total</th>
                    <th style="text-align:right">Received</th>
                    <th style="text-align:right">Billed</th>
                    <th>Ack.</th>
                    <th style="width:130px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                $pos = [
                    ['PO-2025-0412', 'DigiKey Corp', 'standard', 'sent', '2025-07-01', '2025-07-15', 8400, 0, 0, false, 'warning'],
                    ['PO-2025-0411', 'Acme Electronics', 'standard', 'acknowledged', '2025-06-30', '2025-07-12', 24600, 0, 0, true, 'primary'],
                    ['PO-2025-0410', 'MetalMart Inc', 'standard', 'partial', '2025-06-25', '2025-07-05', 4200, 2100, 2100, true, 'info'],
                    ['PO-2025-0409', 'Wire Works LLC', 'standard', 'received', '2025-06-20', '2025-06-30', 1800, 1800, 0, true, 'success'],
                    ['PO-2025-0408', 'Contract Fab Co', 'outsource', 'sent', '2025-06-18', '2025-07-20', 15000, 0, 0, false, 'warning'],
                    ['PO-2025-0407', 'Global Bearings', 'standard', 'closed', '2025-06-10', '2025-06-25', 5700, 5700, 5700, true, 'success'],
                ];
                @endphp
                @foreach($pos as $po)
                @php
                $statusColors = ['draft'=>'secondary','sent'=>'warning','acknowledged'=>'primary','partial'=>'info','received'=>'success','closed'=>'success'];
                $typeColors = ['standard'=>'secondary','outsource'=>'purple','internal'=>'info'];
                $receivedPct = $po[6] > 0 ? round(($po[7]/$po[6])*100) : 0;
                @endphp
                <tr>
                    <td><input type="checkbox" class="row-select"></td>
                    <td>
                        <a href="{{ route('purchasing.purchase-orders.show', 1) }}" style="color:var(--accent);font-weight:600;font-family:'DM Mono',monospace;font-size:12.5px">{{ $po[0] }}</a>
                    </td>
                    <td style="font-weight:500;font-size:13.5px">{{ $po[1] }}</td>
                    <td>
                        <span class="badge badge-{{ $typeColors[$po[2]] ?? 'secondary' }}" style="font-size:10px">
                            {{ ucfirst($po[2]) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $statusColors[$po[3]] ?? 'secondary' }}">
                            {{ ucfirst($po[3]) }}
                        </span>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $po[4] }}</td>
                    <td style="font-size:12.5px;{{ $po[10] === 'warning' ? 'color:var(--danger);font-weight:600' : 'color:var(--text-muted)' }}">
                        {{ $po[5] }}
                    </td>
                    <td style="text-align:right;font-weight:600;font-family:'DM Mono',monospace;font-size:12.5px">
                        ${{ number_format($po[6]) }}
                    </td>
                    <td style="text-align:right">
                        <div>
                            <span style="font-family:'DM Mono',monospace;font-size:12px;color:{{ $po[7] > 0 ? 'var(--success)' : 'var(--text-muted)' }};font-weight:{{ $po[7] > 0 ? '600' : '400' }}">
                                ${{ number_format($po[7]) }}
                            </span>
                        </div>
                        @if($receivedPct > 0 && $receivedPct < 100)
                        <div style="width:60px;height:4px;background:#f1f5f9;border-radius:2px;margin-left:auto;margin-top:3px">
                            <div style="width:{{ $receivedPct }}%;height:100%;background:var(--success);border-radius:2px"></div>
                        </div>
                        @endif
                    </td>
                    <td style="text-align:right;font-family:'DM Mono',monospace;font-size:12px;color:{{ $po[8] > 0 ? 'var(--warning)' : 'var(--text-muted)' }}">
                        ${{ number_format($po[8]) }}
                    </td>
                    <td>
                        @if($po[9])
                            <i class="fa-solid fa-check-circle" style="color:var(--success);font-size:14px" title="Acknowledged"></i>
                        @else
                            <i class="fa-regular fa-circle" style="color:var(--text-light);font-size:14px" title="Not acknowledged"></i>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('purchasing.purchase-orders.show', 1) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fa-solid fa-eye" style="font-size:11px"></i>
                            </a>
                            @if($po[3] !== 'closed')
                            <a href="{{ route('purchasing.receiving.index') }}" class="btn btn-success btn-sm btn-icon" title="Receive">
                                <i class="fa-solid fa-boxes-packing" style="font-size:11px"></i>
                            </a>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm btn-icon" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical" style="font-size:11px"></i>
                                </button>
                                <ul class="dropdown-menu" style="font-size:12.5px;min-width:160px">
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-file-pdf me-2"></i>PDF</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-envelope me-2"></i>Email Vendor</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-check me-2"></i>Mark Acknowledged</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-copy me-2"></i>Duplicate</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-ban me-2"></i>Cancel</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush

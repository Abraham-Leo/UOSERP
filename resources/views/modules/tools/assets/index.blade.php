@extends('layouts.app')
@section('title','Tools & Equipment')
@section('breadcrumb') Admin / <span class="current">Tools & Equipment</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-screwdriver-wrench" style="color:var(--accent);margin-right:10px"></i>Tools & Equipment</h1>
        <p class="page-subtitle">Manage tools, machines, calibration and maintenance schedules</p>
    </div>
    <a href="{{ route('tools.assets.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Asset</a>
</div>

<div class="row g-3 mb-4">
    @php
    $assetStats = [
        ['Total Assets','47','accent','fa-screwdriver-wrench'],
        ['Available','38','success','fa-check-circle'],
        ['Checked Out','6','warning','fa-arrow-right-from-bracket'],
        ['Due for Maintenance','3','danger','fa-calendar-xmark'],
    ];
    @endphp
    @foreach($assetStats as [$l,$v,$c,$i])
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
                <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Asset ID, name, serial..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="tool">Tool</option>
                        <option value="machine">Machine</option>
                        <option value="equipment">Equipment</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status')==='available'?'selected':'' }}>Available</option>
                        <option value="checked_out" {{ request('status')==='checked_out'?'selected':'' }}>Checked Out</option>
                        <option value="maintenance" {{ request('status')==='maintenance'?'selected':'' }}>Maintenance</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('tools.assets.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Assets</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Serial #</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Next Maint.</th>
                    <th style="text-align:right">Value</th>
                    <th style="width:140px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets ?? collect() as $asset)
                <tr>
                    <td><a href="{{ route('tools.assets.show',$asset) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $asset->asset_id }}</a></td>
                    <td style="font-weight:500">{{ $asset->name }}</td>
                    <td><span class="badge badge-secondary" style="font-size:10px">{{ ucfirst($asset->type ?? '—') }}</span></td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $asset->serial_number ?? '—' }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $asset->bin_location ?? '—' }}</td>
                    <td>
                        @php $sc = ['available'=>'success','checked_out'=>'warning','maintenance'=>'danger'][$asset->status ?? 'available'] ?? 'secondary'; @endphp
                        <span class="badge badge-{{ $sc }}">{{ ucfirst(str_replace('_',' ',$asset->status ?? 'available')) }}</span>
                    </td>
                    <td style="font-size:12.5px;color:{{ $asset->next_maintenance_date && $asset->next_maintenance_date->isPast() ? 'var(--danger)' : 'var(--text-muted)' }}">
                        {{ $asset->next_maintenance_date?->format('M d, Y') ?? '—' }}
                    </td>
                    <td style="text-align:right;font-family:monospace">${{ number_format($asset->purchase_value ?? 0,0) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('tools.assets.show',$asset) }}" class="btn btn-secondary btn-sm btn-icon" title="View"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('tools.assets.edit',$asset) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fa-solid fa-pen" style="font-size:11px"></i></a>
                            @if(($asset->status ?? 'available') === 'available')
                            <form method="POST" action="{{ route('tools.assets.checkout',$asset) }}">
                                @csrf<button type="submit" class="btn btn-warning btn-sm btn-icon" title="Check Out"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:11px"></i></button>
                            </form>
                            @elseif(($asset->status ?? '') === 'checked_out')
                            <form method="POST" action="{{ route('tools.assets.checkin',$asset) }}">
                                @csrf<button type="submit" class="btn btn-success btn-sm btn-icon" title="Check In"><i class="fa-solid fa-arrow-right-to-bracket" style="font-size:11px"></i></button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('tools.assets.destroy',$asset) }}" onsubmit="return confirm('Delete asset?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Demo data --}}
                @php
                $demoAssets = [
                    ['TOOL-001','Torque Wrench 50Nm','tool','TW-50-001','A-1-01','available','2025-08-15',245],
                    ['TOOL-002','Digital Caliper 300mm','tool','DC-300-042','A-1-02','available','2025-09-01',89],
                    ['MACH-001','CNC Milling Machine','machine','CNC-VMC-2024','Shop Floor Bay 1','available','2025-07-20',45000],
                    ['MACH-002','Soldering Station','machine','SS-500-007','Assembly Area','checked_out','2025-10-01',320],
                    ['EQUIP-001','Oscilloscope 200MHz','equipment','OSC-200-003','Test Bench','available','2025-12-01',1800],
                    ['TOOL-003','Heat Gun','tool','HG-100-015','Storage B-2','maintenance',null,65],
                ];
                @endphp
                @foreach($demoAssets as $a)
                <tr>
                    <td><span style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $a[0] }}</span></td>
                    <td style="font-weight:500">{{ $a[1] }}</td>
                    <td><span class="badge badge-secondary" style="font-size:10px">{{ ucfirst($a[2]) }}</span></td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $a[3] }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $a[4] }}</td>
                    <td>
                        @php $sc = ['available'=>'success','checked_out'=>'warning','maintenance'=>'danger'][$a[5]] ?? 'secondary'; @endphp
                        <span class="badge badge-{{ $sc }}">{{ ucfirst(str_replace('_',' ',$a[5])) }}</span>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $a[6] ?? '—' }}</td>
                    <td style="text-align:right;font-family:monospace">${{ number_format($a[7],0) }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-secondary btn-sm btn-icon" title="View"><i class="fa-solid fa-eye" style="font-size:11px"></i></button>
                            <button class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fa-solid fa-pen" style="font-size:11px"></i></button>
                            @if($a[5] === 'available')
                            <button class="btn btn-warning btn-sm btn-icon" title="Check Out" onclick="showToast('Check out recorded','success')"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:11px"></i></button>
                            @elseif($a[5] === 'checked_out')
                            <button class="btn btn-success btn-sm btn-icon" title="Check In" onclick="showToast('Checked in successfully','success')"><i class="fa-solid fa-arrow-right-to-bracket" style="font-size:11px"></i></button>
                            @endif
                            <button class="btn btn-secondary btn-sm btn-icon" title="Log Maintenance" onclick="showMaintenanceModal('{{ $a[0] }}')"><i class="fa-solid fa-wrench" style="font-size:11px;color:var(--warning)"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($assets) && method_exists($assets,'hasPages') && $assets->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $assets->links() }}</div>
    @endif
</div>

{{-- Maintenance Modal --}}
<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px">
            <div class="modal-header" style="border-bottom:1px solid var(--border)">
                <h5 class="modal-title" style="font-size:15px;font-weight:600"><i class="fa-solid fa-wrench" style="color:var(--warning);margin-right:8px"></i>Log Maintenance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="maintAssetId">
                <div class="mb-3">
                    <label class="form-label">Maintenance Type</label>
                    <select class="form-select">
                        <option>Calibration</option>
                        <option>Preventive Maintenance</option>
                        <option>Repair</option>
                        <option>Inspection</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Result</label>
                    <select class="form-select"><option>Pass</option><option>Fail</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Next Due Date</label>
                    <input type="date" class="form-control" value="{{ now()->addYear()->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border)">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveMaintenance()">Save Record</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function showMaintenanceModal(assetId) {
    document.getElementById('maintAssetId').value = assetId;
    new bootstrap.Modal(document.getElementById('maintenanceModal')).show();
}
function saveMaintenance() {
    bootstrap.Modal.getInstance(document.getElementById('maintenanceModal'))?.hide();
    showToast('Maintenance record saved', 'success');
}
</script>
@endpush

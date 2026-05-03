@extends('layouts.app')
@section('title','Shipments')
@section('breadcrumb') Shipping / <span class="current">Shipments</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-truck-fast" style="color:var(--accent);margin-right:10px"></i>Shipments</h1>
        <p class="page-subtitle">Track outbound shipments and deliveries</p>
    </div>
    <a href="{{ route('shipping.shipments.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Shipment</a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Pending','0','warning','fa-clock'],
        ['Shipped Today','0','success','fa-truck-fast'],
        ['In Transit','0','info','fa-route'],
        ['Delivered','0','success','fa-check-circle'],
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
                <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Shipment #, tracking, customer..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['pending','shipped','delivered'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('shipping.shipments.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Shipments</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Shipment #</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Ship Date</th>
                    <th>Carrier</th>
                    <th>Tracking #</th>
                    <th>Weight</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipments ?? collect() as $shp)
                <tr>
                    <td><a href="{{ route('shipping.shipments.show',$shp) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $shp->shipment_number }}</a></td>
                    <td><a href="{{ route('sales.orders.show',$shp->order) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px">{{ $shp->order->order_number }}</a></td>
                    <td style="font-weight:500">{{ $shp->customer->name }}</td>
                    <td><x-status-badge :status="$shp->status" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $shp->ship_date->format('M d, Y') }}</td>
                    <td style="font-size:13px">{{ $shp->carrier ?? '—' }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--accent)">{{ $shp->tracking_number ?? '—' }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $shp->weight ? number_format($shp->weight,2).' lbs' : '—' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('shipping.shipments.show',$shp) }}" class="btn btn-secondary btn-sm btn-icon" title="View"><i class="fa-solid fa-eye" style="font-size:11px"></i></a>
                            <a href="{{ route('shipping.shipments.label',$shp) }}" class="btn btn-secondary btn-sm btn-icon" title="Print Label" target="_blank"><i class="fa-solid fa-print" style="font-size:11px"></i></a>
                            @if($shp->status === 'pending')
                            <form method="POST" action="{{ route('shipping.shipments.ship',$shp) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm btn-icon" title="Mark Shipped">
                                    <i class="fa-solid fa-truck" style="font-size:11px"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Demo data --}}
                @php
                $demoShipments = [
                    ['SHP-2025-0089','SO-2025-0845','Global MFG','shipped','2025-07-03','UPS Ground','1Z999AA10123456784','12.4'],
                    ['SHP-2025-0088','SO-2025-0844','Pacific Steel','delivered','2025-07-01','FedEx','789456123987','8.2'],
                    ['SHP-2025-0087','SO-2025-0843','Nexus Parts','pending','2025-07-05','UPS 2nd Day','','5.6'],
                    ['SHP-2025-0086','SO-2025-0840','TechCorp LLC','delivered','2025-06-28','USPS Priority','9400111899223422','2.1'],
                    ['SHP-2025-0085','SO-2025-0838','Acme Industries','shipped','2025-07-02','FedEx Express','722777777777','18.8'],
                ];
                @endphp
                @foreach($demoShipments as $d)
                <tr>
                    <td><span style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $d[0] }}</span></td>
                    <td><span style="color:var(--accent);font-family:monospace;font-size:12.5px">{{ $d[1] }}</span></td>
                    <td style="font-weight:500">{{ $d[2] }}</td>
                    <td><x-status-badge :status="$d[3]" /></td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $d[4] }}</td>
                    <td style="font-size:13px">{{ $d[5] }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--accent)">{{ $d[6] ?: '—' }}</td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $d[7] }} lbs</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-secondary btn-sm btn-icon" title="View"><i class="fa-solid fa-eye" style="font-size:11px"></i></button>
                            <button class="btn btn-secondary btn-sm btn-icon" title="Print Label" onclick="showToast('Printing label...','info')"><i class="fa-solid fa-print" style="font-size:11px"></i></button>
                            @if($d[3] === 'pending')
                            <button class="btn btn-success btn-sm btn-icon" title="Mark Shipped" onclick="showToast('Shipment marked as shipped','success')"><i class="fa-solid fa-truck" style="font-size:11px"></i></button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($shipments) && method_exists($shipments,'hasPages') && $shipments->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $shipments->links() }}</div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Parts / Items')
@section('breadcrumb')
    Inventory / <span class="current">Parts</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-microchip" style="color:var(--accent);margin-right:10px"></i>Parts / Items</h1>
        <p class="page-subtitle">Manage all materials, components, subassemblies, and finished goods</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary" onclick="importParts()">
            <i class="fa-solid fa-file-import"></i> Import
        </button>
        <button class="btn btn-secondary" onclick="exportParts()">
            <i class="fa-solid fa-file-excel"></i> Export
        </button>
        <a href="{{ route('inventory.parts.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Part
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card" style="padding:14px;text-align:center">
            <div style="font-size:22px;font-weight:700;color:var(--accent)">{{ $stats['total'] ?? 2847 }}</div>
            <div style="font-size:11.5px;color:var(--text-muted)">Total Parts</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card" style="padding:14px;text-align:center">
            <div style="font-size:22px;font-weight:700;color:var(--success)">{{ $stats['active'] ?? 2610 }}</div>
            <div style="font-size:11.5px;color:var(--text-muted)">Active</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card" style="padding:14px;text-align:center">
            <div style="font-size:22px;font-weight:700;color:var(--warning)">{{ $stats['low_stock'] ?? 28 }}</div>
            <div style="font-size:11.5px;color:var(--text-muted)">Low Stock</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card" style="padding:14px;text-align:center">
            <div style="font-size:22px;font-weight:700;color:var(--danger)">{{ $stats['out_of_stock'] ?? 12 }}</div>
            <div style="font-size:11.5px;color:var(--text-muted)">Out of Stock</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card" style="padding:14px;text-align:center">
            <div style="font-size:22px;font-weight:700;color:var(--purple)">{{ $stats['with_bom'] ?? 384 }}</div>
            <div style="font-size:11.5px;color:var(--text-muted)">Have BOM</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card" style="padding:14px;text-align:center">
            <div style="font-size:22px;font-weight:700;color:var(--info)">${{ number_format($stats['inv_value'] ?? 2100000, 0) }}</div>
            <div style="font-size:11.5px;color:var(--text-muted)">Inventory Value</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Part #, description..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="component">Component</option>
                        <option value="subassembly">Subassembly</option>
                        <option value="finished_good">Finished Good</option>
                        <option value="raw_material">Raw Material</option>
                        <option value="service">Service Item</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="make_buy" class="form-select">
                        <option value="">Make / Buy</option>
                        <option value="buy">Buy Only</option>
                        <option value="make">Make Only</option>
                        <option value="either">Either</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock_status" class="form-select">
                        <option value="">All Stock</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low">Low Stock</option>
                        <option value="out">Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('inventory.parts.index') }}" class="btn btn-secondary w-100">
                        <i class="fa-solid fa-xmark"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Parts Table -->
<div class="card table-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-layer-group" style="color:var(--accent)"></i> Parts List
        </div>
        <div style="display:flex;gap:6px">
            <button class="btn btn-secondary btn-sm" onclick="toggleView('grid')" id="gridViewBtn">
                <i class="fa-solid fa-grid-2"></i>
            </button>
            <button class="btn btn-secondary btn-sm active" onclick="toggleView('list')" id="listViewBtn" style="background:var(--accent-soft);border-color:var(--accent);color:var(--accent)">
                <i class="fa-solid fa-list"></i>
            </button>
        </div>
    </div>
    <div style="overflow-x:auto" id="listView">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Part Number</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Make/Buy</th>
                    <th style="text-align:right">QOH</th>
                    <th style="text-align:right">QR</th>
                    <th style="text-align:right">QOO</th>
                    <th style="text-align:right">Avail.</th>
                    <th style="text-align:right">Std. Cost</th>
                    <th>UOM</th>
                    <th>BOM</th>
                    <th style="width:110px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                $parts = [
                    ['COMP-0042', 'Capacitor 100uF 25V SMD', 'component', 'buy', 150, 0, 500, 650, 0.12, 'EA', false, 'warning'],
                    ['COMP-0091', 'IC Chip STM32F407VGT6', 'component', 'buy', 30, 50, 0, -20, 8.45, 'EA', false, 'danger'],
                    ['MECH-0033', 'Bearing 6204 2RS Sealed', 'component', 'buy', 200, 120, 100, 180, 2.85, 'EA', false, 'success'],
                    ['RAW-0012', 'Steel Sheet 304 SS 2mm', 'raw_material', 'buy', 15, 0, 25, 40, 4.20, 'SQFT', false, 'success'],
                    ['SUB-0018', 'Control Board Rev C', 'subassembly', 'make', 5, 10, 0, -5, 145.00, 'EA', true, 'danger'],
                    ['FG-0001', 'Motor Controller Unit v3', 'finished_good', 'make', 8, 25, 0, -17, 482.00, 'EA', true, 'danger'],
                    ['COMP-0055', 'Resistor 10kΩ 0402 1%', 'component', 'buy', 5000, 200, 0, 4800, 0.008, 'EA', false, 'success'],
                    ['MECH-0019', 'Aluminum Bracket L40×40', 'component', 'buy', 100, 0, 0, 100, 3.50, 'EA', false, 'success'],
                ];
                @endphp
                @foreach($parts as $p)
                @php
                $typeColors = ['component'=>'primary','subassembly'=>'purple','finished_good'=>'success','raw_material'=>'warning','service'=>'info'];
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('inventory.parts.show', 1) }}" style="color:var(--accent);font-weight:600;font-family:'DM Mono',monospace;font-size:12.5px">{{ $p[0] }}</a>
                    </td>
                    <td style="font-weight:500;font-size:13.5px;max-width:200px">{{ $p[1] }}</td>
                    <td>
                        <span class="badge badge-{{ $typeColors[$p[2]] ?? 'secondary' }}" style="font-size:10px">
                            {{ ucfirst(str_replace('_',' ',$p[2])) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $p[3] === 'buy' ? 'info' : 'purple' }}" style="font-size:10px">
                            {{ strtoupper($p[3]) }}
                        </span>
                    </td>
                    <td style="text-align:right;font-weight:600;font-family:'DM Mono',monospace;font-size:12.5px">
                        {{ number_format($p[4]) }}
                    </td>
                    <td style="text-align:right;font-family:'DM Mono',monospace;font-size:12px;color:var(--warning)">
                        {{ $p[5] > 0 ? number_format($p[5]) : '—' }}
                    </td>
                    <td style="text-align:right;font-family:'DM Mono',monospace;font-size:12px;color:var(--success)">
                        {{ $p[6] > 0 ? '+'.number_format($p[6]) : '—' }}
                    </td>
                    <td style="text-align:right;font-weight:700;font-family:'DM Mono',monospace;font-size:12.5px">
                        @if($p[7] < 0)
                            <span style="color:var(--danger)">{{ number_format($p[7]) }}</span>
                        @elseif($p[7] < 20)
                            <span style="color:var(--warning)">{{ number_format($p[7]) }}</span>
                        @else
                            <span style="color:var(--success)">{{ number_format($p[7]) }}</span>
                        @endif
                    </td>
                    <td style="text-align:right;font-family:'DM Mono',monospace;font-size:12.5px">
                        ${{ number_format($p[8], $p[8] < 1 ? 4 : 2) }}
                    </td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $p[9] }}</td>
                    <td>
                        @if($p[10])
                            <a href="{{ route('inventory.boms.index') }}" style="font-size:12px;color:var(--success)">
                                <i class="fa-solid fa-sitemap"></i> Yes
                            </a>
                        @else
                            <span style="font-size:12px;color:var(--text-light)">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('inventory.parts.show', 1) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fa-solid fa-eye" style="font-size:11px"></i>
                            </a>
                            <a href="{{ route('inventory.parts.edit', 1) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fa-solid fa-pen" style="font-size:11px"></i>
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm btn-icon" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical" style="font-size:11px"></i>
                                </button>
                                <ul class="dropdown-menu" style="font-size:12.5px;min-width:160px">
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-sitemap me-2"></i>View BOM</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-shopping-cart me-2"></i>Create PO</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-history me-2"></i>History</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-barcode me-2"></i>Print Label</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-body" style="padding:14px 20px;border-top:1px solid var(--border)">
        <div style="font-size:12.5px;color:var(--text-muted)">
            Legend:
            <span style="color:var(--success);margin-left:12px"><i class="fa-solid fa-circle" style="font-size:8px"></i> In Stock</span>
            <span style="color:var(--warning);margin-left:8px"><i class="fa-solid fa-circle" style="font-size:8px"></i> Low Stock</span>
            <span style="color:var(--danger);margin-left:8px"><i class="fa-solid fa-circle" style="font-size:8px"></i> Shortage / Out</span>
            <span style="margin-left:16px">QOH = Qty On Hand · QR = Qty Reserved · QOO = Qty On Order · Avail. = Available</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleView(v) {
    document.getElementById('listView').style.display = v === 'list' ? '' : 'none';
}
</script>
@endpush

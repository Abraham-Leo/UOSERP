@extends('layouts.app')
@section('title','Stock & Warehouse')
@section('breadcrumb') Inventory / <span class="current">Stock / Warehouse</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-warehouse" style="color:var(--accent);margin-right:10px"></i>Warehouse & Stock</h1>
        <p class="page-subtitle">View and manage inventory levels across all locations</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#adjustModal">
            <i class="fa-solid fa-sliders"></i> Adjust Stock
        </button>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#transferModal">
            <i class="fa-solid fa-arrows-left-right"></i> Transfer
        </button>
        <a href="{{ route('inventory.cycle-count') }}" class="btn btn-primary">
            <i class="fa-solid fa-clipboard-check"></i> Cycle Count
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="{{ route('inventory.stock.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Part #, description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="warehouse_id" class="form-select">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses ?? \App\Models\Warehouse::where('is_active',true)->get() as $w)
                        <option value="{{ $w->id }}" {{ request('warehouse_id')==$w->id?'selected':'' }}>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock_status" class="form-select">
                        <option value="">All Stock</option>
                        <option value="in_stock" {{ request('stock_status')==='in_stock'?'selected':'' }}>In Stock</option>
                        <option value="low" {{ request('stock_status')==='low'?'selected':'' }}>Low Stock</option>
                        <option value="out" {{ request('stock_status')==='out'?'selected':'' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i></button></div>
                <div class="col-md-2"><a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary w-100">Clear</a></div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-boxes-stacked" style="color:var(--accent)"></i> Inventory Levels</div>
        <span style="font-size:12px;color:var(--text-muted)">
            <span style="color:var(--success)">●</span> In Stock &nbsp;
            <span style="color:var(--warning)">●</span> Low &nbsp;
            <span style="color:var(--danger)">●</span> Out
        </span>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Part #</th>
                    <th>Description</th>
                    <th>Warehouse</th>
                    <th>Bin</th>
                    <th style="text-align:right">On Hand</th>
                    <th style="text-align:right">Reserved</th>
                    <th style="text-align:right">On Order</th>
                    <th style="text-align:right">Available</th>
                    <th style="text-align:right">ROP</th>
                    <th style="text-align:right">Unit Cost</th>
                    <th style="width:80px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventory ?? collect() as $inv)
                @php
                    $avail = $inv->qty_on_hand - $inv->qty_reserved;
                    $color = $inv->qty_on_hand <= 0 ? 'danger' : ($inv->qty_on_hand <= ($inv->part->reorder_point ?? 0) ? 'warning' : 'success');
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:8px;height:8px;border-radius:50%;background:var(--{{ $color }});flex-shrink:0"></div>
                            <a href="{{ route('inventory.parts.show',$inv->part) }}" style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $inv->part->part_number }}</a>
                        </div>
                    </td>
                    <td style="font-weight:500;font-size:13.5px">{{ Str::limit($inv->part->description,40) }}</td>
                    <td style="font-size:13px">{{ $inv->warehouse->name }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $inv->binLocation->code ?? 'Default' }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:var(--{{ $color }})">{{ number_format($inv->qty_on_hand,2) }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--warning)">{{ $inv->qty_reserved > 0 ? number_format($inv->qty_reserved,2) : '—' }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--success)">{{ $inv->qty_on_order > 0 ? '+'.number_format($inv->qty_on_order,2) : '—' }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:var(--{{ $avail >= 0 ? 'success' : 'danger' }})">{{ number_format($avail,2) }}</td>
                    <td style="text-align:right;font-family:monospace;font-size:12px;color:var(--text-muted)">{{ number_format($inv->part->reorder_point ?? 0,0) }}</td>
                    <td style="text-align:right;font-family:monospace">${{ number_format($inv->unit_cost,4) }}</td>
                    <td>
                        <button class="btn btn-secondary btn-sm btn-icon" title="Adjust"
                            onclick="openAdjust('{{ $inv->id }}','{{ $inv->part->part_number }}','{{ $inv->qty_on_hand }}')">
                            <i class="fa-solid fa-sliders" style="font-size:11px"></i>
                        </button>
                    </td>
                </tr>
                @empty
                {{-- Demo data when no inventory records --}}
                @php
                $demoInv = [
                    ['COMP-0042','Capacitor 100uF 25V','Main Warehouse','A-1-01',150,0,500,50,0.12,'success'],
                    ['COMP-0091','IC Chip STM32F407VGT6','Main Warehouse','A-1-02',30,50,0,50,7.80,'danger'],
                    ['MECH-0033','Bearing 6204 2RS','Main Warehouse','B-2-03',200,120,100,80,2.50,'success'],
                    ['RAW-0012','Steel Sheet 304 SS 2mm','Main Warehouse','C-1-01',15,0,25,0,3.80,'warning'],
                    ['SUB-0018','Control Board Rev C','WIP Storage','WIP-01',5,10,0,50,120.00,'danger'],
                    ['FG-0001','Motor Controller Unit v3','Finished Goods','FG-01',8,25,0,50,400.00,'danger'],
                    ['COMP-0055','Resistor 10kΩ 0402','Main Warehouse','A-3-05',5000,200,0,20,0.006,'success'],
                    ['MECH-0019','Aluminum Bracket L40','Main Warehouse','B-1-02',100,0,0,20,3.00,'success'],
                ];
                @endphp
                @foreach($demoInv as $d)
                @php $avail = $d[4] - $d[5]; $color = $d[9]; @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:8px;height:8px;border-radius:50%;background:var(--{{ $color }});flex-shrink:0"></div>
                            <span style="color:var(--accent);font-family:monospace;font-weight:600;font-size:12.5px">{{ $d[0] }}</span>
                        </div>
                    </td>
                    <td style="font-weight:500;font-size:13.5px">{{ $d[1] }}</td>
                    <td style="font-size:13px">{{ $d[2] }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $d[3] }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:var(--{{ $color }})">{{ number_format($d[4],2) }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--warning)">{{ $d[5] > 0 ? number_format($d[5],2) : '—' }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--success)">{{ $d[6] > 0 ? '+'.number_format($d[6],2) : '—' }}</td>
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:var(--{{ $avail >= 0 ? 'success' : 'danger' }})">{{ number_format($avail,2) }}</td>
                    <td style="text-align:right;font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $d[8] }}</td>
                    <td style="text-align:right;font-family:monospace">${{ number_format($d[9] === 'success' ? $d[8] * 100 : $d[8],4) }}</td>
                    <td>
                        <button class="btn btn-secondary btn-sm btn-icon" title="Adjust"
                            onclick="openAdjust('demo-{{ $loop->index }}','{{ $d[0] }}','{{ $d[4] }}')">
                            <i class="fa-solid fa-sliders" style="font-size:11px"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($inventory) && method_exists($inventory,'hasPages') && $inventory->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $inventory->links() }}</div>
    @endif
</div>

{{-- Adjust Modal --}}
<div class="modal fade" id="adjustModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px">
            <div class="modal-header" style="border-bottom:1px solid var(--border)">
                <h5 class="modal-title" style="font-size:15px;font-weight:600">
                    <i class="fa-solid fa-sliders" style="color:var(--accent);margin-right:8px"></i>Adjust Inventory
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('inventory.stock.adjust') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="inventory_id" id="adj_inv_id">
                    <div class="mb-3">
                        <label class="form-label">Part</label>
                        <input type="text" id="adj_part" class="form-control" readonly style="background:var(--bg)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Qty</label>
                        <input type="text" id="adj_current" class="form-control" readonly style="background:var(--bg)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adjustment Type</label>
                        <select name="adj_type" class="form-select">
                            <option value="add">Add (+)</option>
                            <option value="subtract">Subtract (−)</option>
                            <option value="set">Set Exact Qty</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity <span style="color:var(--danger)">*</span></label>
                        <input type="number" name="quantity" class="form-control" min="0" step="0.0001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <select name="reason" class="form-select">
                            @foreach(['Cycle Count','Receiving','Scrap / Write-off','Return to Stock','Physical Inventory','Damaged','Other'] as $r)
                            <option>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Reason for adjustment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border)">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> Apply Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Transfer Modal --}}
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px">
            <div class="modal-header" style="border-bottom:1px solid var(--border)">
                <h5 class="modal-title" style="font-size:15px;font-weight:600">
                    <i class="fa-solid fa-arrows-left-right" style="color:var(--accent);margin-right:8px"></i>Transfer Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('inventory.stock.transfer') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Part <span style="color:var(--danger)">*</span></label>
                            <select name="part_id" class="form-select select2" required>
                                <option value="">— Select Part —</option>
                                @foreach($parts ?? \App\Models\Part::where('is_active',true)->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->part_number }} — {{ $p->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">From Warehouse <span style="color:var(--danger)">*</span></label>
                            <select name="from_warehouse_id" class="form-select" required>
                                @foreach($warehouses ?? \App\Models\Warehouse::where('is_active',true)->get() as $w)
                                <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">To Warehouse <span style="color:var(--danger)">*</span></label>
                            <select name="to_warehouse_id" class="form-select" required>
                                @foreach($warehouses ?? \App\Models\Warehouse::where('is_active',true)->get() as $w)
                                <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Quantity <span style="color:var(--danger)">*</span></label>
                            <input type="number" name="quantity" class="form-control" min="0.0001" step="0.0001" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Notes</label>
                            <input type="text" name="notes" class="form-control" placeholder="Reason...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border)">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-arrows-left-right"></i> Transfer Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function openAdjust(invId, partNum, currentQty) {
    document.getElementById('adj_inv_id').value = invId;
    document.getElementById('adj_part').value = partNum;
    document.getElementById('adj_current').value = currentQty;
    new bootstrap.Modal(document.getElementById('adjustModal')).show();
}
</script>
@endpush

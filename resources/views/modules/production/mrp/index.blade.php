@extends('layouts.app')
@section('title','MRP')
@section('breadcrumb') Production / <span class="current">MRP</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-chart-network" style="color:var(--purple);margin-right:10px"></i>Material Requirements Planning</h1>
        <p class="page-subtitle">Analyze demand vs supply — shortages, reorder points, auto-generate POs and Work Orders</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary" onclick="exportMRP()">
            <i class="fa-solid fa-file-excel"></i> Export
        </button>
        <button class="btn btn-warning" id="runMrpBtn" onclick="runMRP()">
            <i class="fa-solid fa-play"></i> Run MRP
        </button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body" style="padding:16px 20px">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Planning Horizon</label>
                <select class="form-select" id="planningHorizon">
                    <option value="30">Next 30 Days</option>
                    <option value="60" selected>Next 60 Days</option>
                    <option value="90">Next 90 Days</option>
                    <option value="180">Next 180 Days</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Show</label>
                <select class="form-select" id="mrpFilter">
                    <option value="all">All Items</option>
                    <option value="shortage" selected>Shortages Only</option>
                    <option value="reorder">Below Reorder Point</option>
                    <option value="buy">Buy Items</option>
                    <option value="make">Make Items</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select class="form-select">
                    <option value="">All Categories</option>
                    <option>Electronic Components</option>
                    <option>Mechanical Parts</option>
                    <option>Raw Materials</option>
                    <option>Finished Goods</option>
                </select>
            </div>
            <div class="col-md-3">
                <div style="display:flex;gap:16px;align-items:center;padding-top:20px">
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                        <input type="checkbox" checked style="accent-color:var(--accent)"> Safety Stock
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                        <input type="checkbox" checked style="accent-color:var(--accent)"> Include WIP
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card" style="padding:16px;border-left:4px solid var(--danger)">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:44px;height:44px;background:rgba(239,68,68,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--danger)">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                </div>
                <div><div style="font-size:24px;font-weight:700">12</div><div style="font-size:12px;color:var(--text-muted)">Critical Shortages</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="padding:16px;border-left:4px solid var(--warning)">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:44px;height:44px;background:rgba(245,158,11,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--warning)">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>
                <div><div style="font-size:24px;font-weight:700">28</div><div style="font-size:12px;color:var(--text-muted)">Below Reorder Point</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="padding:16px;border-left:4px solid var(--accent)">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:44px;height:44px;background:rgba(59,130,246,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--accent)">
                    <i class="fa-solid fa-shopping-cart"></i>
                </div>
                <div><div style="font-size:24px;font-weight:700">$84.2K</div><div style="font-size:12px;color:var(--text-muted)">Suggested PO Value</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="padding:16px;border-left:4px solid var(--success)">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:44px;height:44px;background:rgba(16,185,129,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--success)">
                    <i class="fa-solid fa-gears"></i>
                </div>
                <div><div style="font-size:24px;font-weight:700">7</div><div style="font-size:12px;color:var(--text-muted)">Suggested Work Orders</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--purple)"></i> MRP Requirements List</div>
        <div class="d-flex gap-2">
            <button class="btn btn-danger btn-sm" onclick="autoPO()">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Create POs
            </button>
            <button class="btn btn-success btn-sm" onclick="autoWO()">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Create WOs
            </button>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" onchange="document.querySelectorAll('.row-select').forEach(cb=>cb.checked=this.checked)"></th>
                    <th>Part #</th>
                    <th>Description</th>
                    <th>Make/Buy</th>
                    <th style="text-align:right">On Hand</th>
                    <th style="text-align:right">On Order</th>
                    <th style="text-align:right">Needed</th>
                    <th style="text-align:right">Net Req.</th>
                    <th>Vendor/Buyer</th>
                    <th>Buy Date</th>
                    <th>Lead Time</th>
                    <th style="width:90px">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                $mrpRows = [
                    ['COMP-0042','Capacitor 100uF 25V','buy',150,0,800,650,'Acme Electronics','2025-07-02','14 days','danger'],
                    ['COMP-0091','IC Chip STM32F4','buy',30,50,200,120,'DigiKey','2025-07-05','21 days','danger'],
                    ['RAW-0012','Steel Sheet 2mm','buy',5,0,50,45,'MetalMart','2025-07-01','7 days','danger'],
                    ['MECH-0033','Bearing 6204 2RS','buy',200,100,450,150,'Global Bearings','2025-07-08','10 days','warning'],
                    ['COMP-0055','Resistor 10k Ohm','buy',2000,5000,8000,1000,'Mouser','2025-07-10','5 days','warning'],
                    ['SUB-0018','Control Board Rev C','make',5,0,25,20,'Internal','2025-07-03','10 days','danger'],
                    ['MECH-0019','Aluminum Bracket L40','buy',100,0,120,20,'Local Fab','2025-07-12','5 days','warning'],
                    ['COMP-0078','Transformer 24V 2A','buy',45,20,60,-5,'TDK Corp','—','21 days','success'],
                    ['RAW-0008','Copper Wire 18AWG','buy',500,200,800,100,'Wire Works','2025-07-15','3 days','warning'],
                    ['COMP-0102','MOSFET IRF540N','buy',200,100,150,-150,'—','—','7 days','success'],
                ];
                @endphp
                @foreach($mrpRows as $row)
                <tr style="{{ $row[10] === 'danger' ? 'background:rgba(239,68,68,0.03)' : '' }}">
                    <td><input type="checkbox" class="row-select" value="{{ $row[0] }}"></td>
                    <td>
                        <a href="{{ route('inventory.parts.index') }}" style="color:var(--accent);font-weight:500;font-family:monospace;font-size:12.5px">{{ $row[0] }}</a>
                    </td>
                    <td style="font-size:13.5px;font-weight:500;max-width:180px">{{ $row[1] }}</td>
                    <td>
                        <span class="badge badge-{{ $row[2] === 'buy' ? 'primary' : 'purple' }}">
                            <i class="fa-solid fa-{{ $row[2] === 'buy' ? 'shopping-cart' : 'gear' }}" style="font-size:9px;margin-right:4px"></i>
                            {{ strtoupper($row[2]) }}
                        </span>
                    </td>
                    <td style="text-align:right;font-weight:600;font-family:monospace">{{ number_format($row[3]) }}</td>
                    <td style="text-align:right;font-family:monospace;color:var(--success)">{{ $row[4] > 0 ? '+'.number_format($row[4]) : '—' }}</td>
                    <td style="text-align:right;font-weight:600;font-family:monospace">{{ number_format($row[5]) }}</td>
                    <td style="text-align:right">
                        @if($row[6] > 0)
                            <span style="color:var(--danger);font-weight:700;font-family:monospace">
                                <i class="fa-solid fa-arrow-down" style="font-size:9px"></i> {{ number_format($row[6]) }}
                            </span>
                        @else
                            <span style="color:var(--success);font-weight:500;font-family:monospace">
                                <i class="fa-solid fa-check" style="font-size:9px"></i> {{ number_format(abs($row[6])) }} surplus
                            </span>
                        @endif
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $row[7] }}</td>
                    <td style="font-size:12.5px;color:{{ $row[10] === 'danger' ? 'var(--danger)' : 'var(--text-muted)' }};font-weight:{{ $row[10] === 'danger' ? '600' : '400' }}">{{ $row[8] }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $row[9] }}</td>
                    <td>
                        @if($row[6] > 0)
                        <div class="d-flex gap-1">
                            @if($row[2] === 'buy')
                            <a href="{{ route('purchasing.purchase-orders.create') }}?part={{ $row[0] }}" class="btn btn-primary btn-sm" title="Create PO">
                                <i class="fa-solid fa-file-circle-plus" style="font-size:10px"></i> PO
                            </a>
                            @else
                            <a href="{{ route('production.work-orders.create') }}?part={{ $row[0] }}" class="btn btn-warning btn-sm" title="Create WO">
                                <i class="fa-solid fa-gears" style="font-size:10px"></i> WO
                            </a>
                            @endif
                        </div>
                        @else
                        <span style="font-size:11.5px;color:var(--success)"><i class="fa-solid fa-circle-check"></i> OK</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-fire" style="color:var(--danger)"></i> Hot / Critical Orders</div></div>
            <div class="card-body" style="padding:0">
                @php
                $hotOrders = [
                    ['SO-2025-0847','Acme Industries','PCB Asm X72','2025-07-05','COMP-0042, COMP-0091'],
                    ['SO-2025-0842','Precision Tools','Motor Ctrl','2025-07-08','RAW-0012'],
                    ['SO-2025-0835','Delta Systems','Control Unit','2025-07-10','SUB-0018'],
                ];
                @endphp
                @foreach($hotOrders as $ho)
                <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:flex-start;gap:12px">
                    <i class="fa-solid fa-fire" style="color:var(--danger);margin-top:2px;flex-shrink:0"></i>
                    <div style="flex:1">
                        <div style="display:flex;justify-content:space-between">
                            <a href="{{ route('sales.orders.index') }}" style="font-family:monospace;font-size:12.5px;color:var(--accent);font-weight:600">{{ $ho[0] }}</a>
                            <span style="font-size:11.5px;color:var(--danger)">Due: {{ $ho[3] }}</span>
                        </div>
                        <div style="font-size:13px;font-weight:500;margin:2px 0">{{ $ho[1] }} — {{ $ho[2] }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted)">
                            Missing: <span style="font-family:monospace;color:var(--danger)">{{ $ho[4] }}</span>
                        </div>
                    </div>
                    <a href="{{ route('purchasing.purchase-orders.create') }}" class="btn btn-danger btn-sm">Expedite</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-chart-bar" style="color:var(--accent)"></i> Demand vs Supply Forecast</div></div>
            <div class="card-body"><canvas id="mrpChart" height="180"></canvas></div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('mrpChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Week 1','Week 2','Week 3','Week 4','Week 5','Week 6','Week 7','Week 8'],
        datasets: [
            { label:'Demand', data:[42000,38000,55000,61000,48000,52000,44000,39000], backgroundColor:'rgba(239,68,68,0.7)', borderRadius:4 },
            { label:'Supply (PO)', data:[30000,45000,40000,50000,55000,40000,45000,50000], backgroundColor:'rgba(59,130,246,0.7)', borderRadius:4 },
            { label:'On Hand', data:[15000,22000,7000,16000,23000,11000,12000,23000], backgroundColor:'rgba(16,185,129,0.7)', borderRadius:4 },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position:'top', labels:{ font:{family:'DM Sans',size:11}, usePointStyle:true } } },
        scales: {
            y: { grid:{color:'#f1f5f9'}, ticks:{ callback: v=>'$'+(v/1000).toFixed(0)+'K', font:{family:'DM Sans',size:11} } },
            x: { grid:{display:false}, ticks:{font:{family:'DM Sans',size:11}} }
        }
    }
});

function runMRP() {
    const btn = document.getElementById('runMrpBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Running MRP...';
    btn.disabled = true;
    setTimeout(() => {
        btn.innerHTML = '<i class="fa-solid fa-play"></i> Run MRP';
        btn.disabled = false;
        showToast('MRP calculation complete — 12 shortages found', 'success');
    }, 2500);
}

function exportMRP() {
    showToast('Preparing MRP export...', 'info');
    setTimeout(() => {
        // Build CSV from table
        const rows = [['Part #','Description','Make/Buy','On Hand','On Order','Needed','Net Req','Vendor','Buy Date','Lead Time']];
        document.querySelectorAll('.erp-table tbody tr').forEach(tr => {
            const cells = [...tr.querySelectorAll('td')].slice(1,11);
            if (cells.length > 0) rows.push(cells.map(td => td.textContent.trim()));
        });
        const csv = rows.map(r => r.map(c => `"${c}"`).join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'mrp-report-' + new Date().toISOString().split('T')[0] + '.csv';
        a.click();
        showToast('MRP exported as CSV', 'success');
    }, 800);
}

function autoPO() {
    const selected = [...document.querySelectorAll('.row-select:checked')].map(cb => cb.value);
    if (!selected.length) { showToast('Select items to create POs for', 'warning'); return; }
    showToast(`Creating ${selected.length} Purchase Order(s)...`, 'info');
    setTimeout(() => showToast('Purchase Orders created successfully', 'success'), 1500);
}

function autoWO() {
    showToast('Creating Work Orders from MRP plan...', 'info');
    setTimeout(() => showToast('Work Orders created successfully', 'success'), 1500);
}
</script>
@endpush

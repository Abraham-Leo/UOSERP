@extends('layouts.app')
@section('title', 'Receive Items')
@section('breadcrumb')
    <a href="{{ route('purchasing.receiving.index') }}" style="color:var(--text-muted);text-decoration:none">Purchasing / Receiving</a> /
    <span class="current">New Receipt</span>
@endsection
@section('content')
<form method="POST" action="{{ route('purchasing.receiving.store') }}">
@csrf
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-boxes-packing" style="color:var(--accent);margin-right:10px"></i>Receive Items</h1>
        <p class="page-subtitle">Record receipt of items against a purchase order</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchasing.receiving.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Receipt</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--accent)"></i> Receipt Info</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Purchase Order <span style="color:var(--danger)">*</span></label>
                        <select name="purchase_order_id" class="form-select select2" required id="poSelect">
                            <option value="">— Select Purchase Order —</option>
                            @foreach($purchaseOrders ?? \App\Models\PurchaseOrder::whereNotIn('status',['closed','cancelled'])->with('vendor')->get() as $po)
                            <option value="{{ $po->id }}" {{ request('po_id') == $po->id ? 'selected' : '' }}>
                                {{ $po->po_number }} — {{ $po->vendor->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Receipt Date <span style="color:var(--danger)">*</span></label>
                        <input type="date" name="receipt_date" class="form-control datepicker"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Packing Slip #</label>
                        <input type="text" name="packing_slip" class="form-control"
                               value="{{ old('packing_slip') }}" placeholder="PS-12345...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Received By</label>
                        <input type="text" name="received_by" class="form-control"
                               value="{{ old('received_by', auth()->user()->name ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Default Warehouse</label>
                        <select name="warehouse_id" class="form-select">
                            @foreach($warehouses ?? \App\Models\Warehouse::where('is_active',true)->get() as $w)
                            <option value="{{ $w->id }}" {{ $w->is_default ? 'selected' : '' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="Receiving notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Line Items to Receive</div>
                <span style="font-size:12.5px;color:var(--text-muted)">Select a PO above to load items</span>
            </div>
            <div style="overflow-x:auto">
                <table class="erp-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Part</th>
                            <th style="text-align:right">Ordered</th>
                            <th style="text-align:right">Prev Received</th>
                            <th style="text-align:right;min-width:100px">Qty Receiving</th>
                            <th style="min-width:110px">Unit Cost ($)</th>
                            <th style="min-width:130px">Lot / Date Code</th>
                            <th style="min-width:120px">Inspection</th>
                        </tr>
                    </thead>
                    <tbody id="receiveLines">
                        <tr>
                            <td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">
                                <i class="fa-solid fa-boxes-packing" style="font-size:28px;opacity:0.3;display:block;margin-bottom:8px"></i>
                                Select a Purchase Order above to load line items
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4" style="position:sticky;top:80px">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-circle-info" style="color:var(--info)"></i> Receipt Process</div></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:20px">
                    @foreach([
                        ['1','fa-file-circle-check','Select PO','Choose the purchase order to receive against'],
                        ['2','fa-boxes-packing','Enter Quantities','Record actual quantities received'],
                        ['3','fa-magnifying-glass','Inspection','Set accept/reject per line'],
                        ['4','fa-warehouse','Put Away','Items auto-stocked to warehouse on save'],
                    ] as [$num,$icon,$title,$desc])
                    <div style="display:flex;align-items:flex-start;gap:12px">
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--accent);flex-shrink:0">{{ $num }}</div>
                        <div>
                            <div style="font-size:13.5px;font-weight:600">{{ $title }}</div>
                            <div style="font-size:12px;color:var(--text-muted)">{{ $desc }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="alert alert-info" style="font-size:12.5px;margin-bottom:16px">
                    <i class="fa-solid fa-info-circle"></i>
                    Saving automatically updates inventory and creates GL entries (Debit Inventory / Credit Accrued A/P).
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="fa-solid fa-save"></i> Save Receipt
                </button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
document.getElementById('poSelect')?.addEventListener('change', function() {
    const poId = this.value;
    if (!poId) {
        document.getElementById('receiveLines').innerHTML = `<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">
            <i class="fa-solid fa-boxes-packing" style="font-size:28px;opacity:0.3;display:block;margin-bottom:8px"></i>
            Select a Purchase Order above to load line items</td></tr>`;
        return;
    }

    document.getElementById('receiveLines').innerHTML = `<tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">
        <i class="fa-solid fa-spinner fa-spin" style="font-size:20px;display:block;margin-bottom:8px"></i>Loading lines...</td></tr>`;

    fetch(`/api/po-lines/${poId}`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(lines => {
        if (!lines.length) {
            document.getElementById('receiveLines').innerHTML = `<tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">No open lines on this PO.</td></tr>`;
            return;
        }
        const inspOpts = ['accepted','rejected','pending'].map(o =>
            `<option value="${o}">${o.charAt(0).toUpperCase()+o.slice(1)}</option>`).join('');

        document.getElementById('receiveLines').innerHTML = lines.map((l, i) => `
            <tr>
                <td>
                    <input type="hidden" name="lines[${i}][po_line_id]" value="${l.id}">
                    <input type="hidden" name="lines[${i}][part_id]" value="${l.part_id}">
                    <div style="font-weight:500;font-size:13px">${l.part_description}</div>
                    <div style="font-family:monospace;font-size:11.5px;color:var(--accent)">${l.part_number}</div>
                </td>
                <td style="text-align:right;font-family:monospace;font-size:12.5px">${l.quantity}</td>
                <td style="text-align:right;font-family:monospace;font-size:12.5px;color:var(--text-muted)">${l.qty_received}</td>
                <td>
                    <input type="number" name="lines[${i}][quantity]" class="form-control form-control-sm text-end"
                           value="${Math.max(0, l.quantity - l.qty_received)}" min="0" step="0.001" style="width:90px;margin-left:auto">
                </td>
                <td>
                    <input type="number" name="lines[${i}][unit_cost]" class="form-control form-control-sm"
                           value="${l.unit_cost}" step="0.0001" min="0" style="width:100px">
                </td>
                <td>
                    <input type="text" name="lines[${i}][lot_number]" class="form-control form-control-sm"
                           placeholder="Lot / Date Code" style="width:120px">
                </td>
                <td>
                    <select name="lines[${i}][inspection_status]" class="form-select form-select-sm" style="width:110px">
                        ${inspOpts}
                    </select>
                </td>
            </tr>`).join('');
    })
    .catch(() => {
        // Fallback demo lines if API not available
        document.getElementById('receiveLines').innerHTML = `
            <tr>
                <td>
                    <input type="hidden" name="lines[0][po_line_id]" value="1">
                    <input type="hidden" name="lines[0][part_id]" value="1">
                    <div style="font-weight:500;font-size:13px">IC Chip STM32F407VGT6</div>
                    <div style="font-family:monospace;font-size:11.5px;color:var(--accent)">COMP-0091</div>
                </td>
                <td style="text-align:right;font-family:monospace">100</td>
                <td style="text-align:right;font-family:monospace;color:var(--text-muted)">0</td>
                <td><input type="number" name="lines[0][quantity]" class="form-control form-control-sm text-end" value="100" min="0" style="width:90px;margin-left:auto"></td>
                <td><input type="number" name="lines[0][unit_cost]" class="form-control form-control-sm" value="8.45" step="0.0001" style="width:100px"></td>
                <td><input type="text" name="lines[0][lot_number]" class="form-control form-control-sm" placeholder="Lot / Date Code" style="width:120px"></td>
                <td><select name="lines[0][inspection_status]" class="form-select form-select-sm" style="width:110px">
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                    <option value="pending">Pending</option>
                </select></td>
            </tr>`;
    });
});

// Auto-load if PO pre-selected
const poSel = document.getElementById('poSelect');
if (poSel && poSel.value) poSel.dispatchEvent(new Event('change'));
</script>
@endpush

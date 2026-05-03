@extends('layouts.app')
@section('title', isset($po->id) ? 'Edit PO' : 'New Purchase Order')
@section('breadcrumb')
    <a href="{{ route('purchasing.purchase-orders.index') }}" style="color:var(--text-muted);text-decoration:none">Purchasing / Purchase Orders</a> /
    <span class="current">{{ isset($po->id) ? $po->po_number : 'New PO' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($po->id) ? route('purchasing.purchase-orders.update',$po) : route('purchasing.purchase-orders.store') }}">
@csrf
@if(isset($po->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($po->id) ? 'Edit: '.$po->po_number : 'New Purchase Order' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchasing.purchase-orders.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save PO</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">PO Header</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Vendor *</label>
                        <select name="vendor_id" class="form-select select2" required>
                            <option value="">— Select Vendor —</option>
                            @foreach($vendors ?? [] as $v)
                            <option value="{{ $v->id }}" {{ old('vendor_id',$po->vendor_id ?? request('vendor_id'))==$v->id?'selected':'' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label">PO Date *</label>
                        <input type="date" name="po_date" class="form-control datepicker" value="{{ old('po_date',$po->po_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div>
                    <div class="col-md-3"><label class="form-label">Requested Date</label>
                        <input type="date" name="requested_date" class="form-control datepicker" value="{{ old('requested_date',$po->requested_date?->format('Y-m-d')) }}"></div>
                    <div class="col-md-4"><label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            @foreach(['standard'=>'Standard','outsource'=>'Outsource','internal'=>'Internal'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('type',$po->type??'standard')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Payment Terms</label>
                        <select name="payment_terms" class="form-select">
                            @foreach(['Net 15','Net 30','Net 45','Net 60','COD','Prepay'] as $t)
                            <option {{ old('payment_terms',$po->payment_terms??'Net 30')===$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Ship Via</label>
                        <input type="text" name="ship_via" class="form-control" value="{{ old('ship_via',$po->ship_via ?? '') }}" placeholder="UPS Ground..."></div>
                    <div class="col-12"><label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes',$po->notes ?? '') }}</textarea></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Line Items</div>
                <button type="button" class="btn btn-primary btn-sm" onclick="addPoLine()"><i class="fa-solid fa-plus"></i> Add Line</button>
            </div>
            <div style="overflow-x:auto">
                <table class="erp-table" style="width:100%">
                    <thead><tr><th>Part</th><th>Qty</th><th>Unit Cost</th><th>Commit Date</th><th style="text-align:right">Total</th><th></th></tr></thead>
                    <tbody id="poLinesBody">
                        @if(isset($po) && $po->lines->count())
                            @foreach($po->lines as $i => $line)
                            <tr class="line-row" data-index="{{ $i }}">
                                <td><input type="hidden" name="lines[{{ $i }}][id]" value="{{ $line->id }}">
                                    <div style="font-weight:500;font-size:13px">{{ $line->part->description }}</div>
                                    <div style="font-family:monospace;font-size:11.5px;color:var(--accent)">{{ $line->part->part_number }}</div>
                                    <input type="hidden" name="lines[{{ $i }}][part_id]" value="{{ $line->part_id }}"></td>
                                <td><input type="number" name="lines[{{ $i }}][quantity]" class="form-control form-control-sm qty-input" value="{{ $line->quantity }}" min="0.0001" step="0.0001" style="width:80px" oninput="calcPoLine({{ $i }})"></td>
                                <td><input type="number" name="lines[{{ $i }}][unit_cost]" class="form-control form-control-sm cost-input" value="{{ $line->unit_cost }}" step="0.0001" style="width:100px" oninput="calcPoLine({{ $i }})"></td>
                                <td><input type="date" name="lines[{{ $i }}][commit_date]" class="form-control form-control-sm datepicker" value="{{ $line->commit_date?->format('Y-m-d') }}" style="width:130px"></td>
                                <td style="text-align:right"><span class="line-total mono" style="font-weight:600">${{ number_format($line->line_total,2) }}</span></td>
                                <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
                            </tr>
                            @endforeach
                        @else
                        <tr><td colspan="6" style="text-align:center;padding:20px;color:var(--text-muted)">
                            No lines. <button type="button" class="btn btn-primary btn-sm ms-2" onclick="addPoLine()"><i class="fa-solid fa-plus"></i> Add Line</button>
                        </td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card" style="position:sticky;top:80px">
            <div class="card-header"><div class="card-title">PO Total</div></div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:700;margin-bottom:16px">
                    <span>Total</span><span class="mono" id="po_total" style="color:var(--accent)">$0.00</span>
                </div>
                <input type="hidden" name="subtotal" id="po_subtotal_h">
                <input type="hidden" name="total" id="po_total_h">
                <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save Purchase Order</button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
let poLineIdx = {{ isset($po) ? max($po->lines->count(), 1) : 0 }};
const partsData = @json($parts ?? []);
function addPoLine() {
    const i = poLineIdx++;
    const opts = partsData.map(p => `<option value="${p.id}" data-cost="${p.unit_cost}">${p.part_number} — ${p.description}</option>`).join('');
    const row = `<tr class="line-row" data-index="${i}">
        <td><select name="lines[${i}][part_id]" class="form-select form-select-sm" onchange="fillPoCost(this,${i})" style="min-width:200px">
            <option value="">— Select Part —</option>${opts}</select></td>
        <td><input type="number" name="lines[${i}][quantity]" class="form-control form-control-sm qty-input" value="1" min="0.0001" step="0.0001" style="width:80px" oninput="calcPoLine(${i})"></td>
        <td><input type="number" name="lines[${i}][unit_cost]" class="form-control form-control-sm cost-input" value="0" step="0.0001" style="width:100px" oninput="calcPoLine(${i})"></td>
        <td><input type="date" name="lines[${i}][commit_date]" class="form-control form-control-sm datepicker" style="width:130px"></td>
        <td style="text-align:right"><span class="line-total mono" style="font-weight:600">$0.00</span></td>
        <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
    </tr>`;
    const tbody = document.getElementById('poLinesBody');
    const empty = tbody.querySelector('td[colspan]');
    if (empty) empty.closest('tr').remove();
    tbody.insertAdjacentHTML('beforeend', row);
}
function fillPoCost(sel, i) {
    document.querySelector(`[data-index="${i}"] .cost-input`).value = sel.options[sel.selectedIndex].getAttribute('data-cost') || 0;
    calcPoLine(i);
}
function removeLine(btn) { btn.closest('tr').remove(); calcPoTotals(); }
function calcPoLine(i) {
    const row = document.querySelector(`[data-index="${i}"]`);
    if (!row) return;
    const total = (parseFloat(row.querySelector('.qty-input').value)||0) * (parseFloat(row.querySelector('.cost-input').value)||0);
    row.querySelector('.line-total').textContent = '$' + total.toFixed(2);
    calcPoTotals();
}
function calcPoTotals() {
    let sub = 0;
    document.querySelectorAll('.line-total').forEach(el => sub += parseFloat(el.textContent.replace('$',''))||0);
    document.getElementById('po_total').textContent = '$' + sub.toFixed(2);
    document.getElementById('po_subtotal_h').value = sub.toFixed(2);
    document.getElementById('po_total_h').value = sub.toFixed(2);
}
document.addEventListener('DOMContentLoaded', () => calcPoTotals());
</script>
@endpush

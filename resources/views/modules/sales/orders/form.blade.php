{{-- ============================================================
     resources/views/modules/sales/orders/form.blade.php
============================================================ --}}
@extends('layouts.app')
@section('title', isset($order->id) ? 'Edit Order' : 'New Order')
@section('breadcrumb')
    <a href="{{ route('sales.orders.index') }}" style="color:var(--text-muted);text-decoration:none">Sales / Orders</a> /
    <span class="current">{{ isset($order->id) ? $order->order_number : 'New Order' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($order->id) ? route('sales.orders.update',$order) : route('sales.orders.store') }}">
@csrf
@if(isset($order->id)) @method('PUT') @endif

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-cart-flatbed" style="color:var(--accent);margin-right:10px"></i>{{ isset($order->id) ? 'Edit: '.$order->order_number : 'New Sales Order' }}</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.orders.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Order</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--accent)"></i> Order Header</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Customer <span style="color:var(--danger)">*</span></label>
                        <select name="customer_id" class="form-select select2" required>
                            <option value="">— Select Customer —</option>
                            @foreach($customers ?? [] as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id',$order->customer_id??request('customer_id'))==$c->id?'selected':'' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Order Type</label>
                        <select name="type" class="form-select">
                            @foreach(['stock'=>'Stock Order','work_order'=>'Work Order','charge'=>'Charge / Service','build_to_stock'=>'Build To Stock'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('type',$order->type??'stock')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['new','in_progress','shipped','invoiced','cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status',$order->status??'new')===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Order Date <span style="color:var(--danger)">*</span></label>
                        <input type="date" name="order_date" class="form-control datepicker" value="{{ old('order_date',$order->order_date?->format('Y-m-d')??now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control datepicker" value="{{ old('due_date',$order->due_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Payment Terms</label>
                        <select name="payment_terms" class="form-select">
                            @foreach(['Net 15','Net 30','Net 45','Net 60','COD','Prepay'] as $t)
                            <option {{ old('payment_terms',$order->payment_terms??'Net 30')===$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Customer PO #</label>
                        <input type="text" name="customer_po" class="form-control" value="{{ old('customer_po',$order->customer_po) }}" placeholder="Customer PO...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Internal Notes</label>
                        <textarea name="internal_notes" class="form-control" rows="2" placeholder="Shop floor / internal notes...">{{ old('internal_notes',$order->internal_notes??'') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Line Items --}}
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Line Items</div>
                <button type="button" class="btn btn-primary btn-sm" onclick="addOrderLine()"><i class="fa-solid fa-plus"></i> Add Line</button>
            </div>
            <div style="overflow-x:auto">
                <table class="erp-table" style="width:100%">
                    <thead><tr><th>Part</th><th>Qty</th><th>Unit Price</th><th>Due Date</th><th style="text-align:right">Total</th><th></th></tr></thead>
                    <tbody id="orderLinesBody">
                        @if(isset($order) && $order->lines->count())
                            @foreach($order->lines as $i => $line)
                            <tr class="line-row" data-index="{{ $i }}">
                                <td>
                                    <input type="hidden" name="lines[{{ $i }}][id]" value="{{ $line->id }}">
                                    <div style="font-weight:500;font-size:13px">{{ $line->part->description }}</div>
                                    <div style="font-family:monospace;font-size:11.5px;color:var(--accent)">{{ $line->part->part_number }}</div>
                                    <input type="hidden" name="lines[{{ $i }}][part_id]" value="{{ $line->part_id }}">
                                    <input type="hidden" name="lines[{{ $i }}][shop_notes]" value="{{ $line->shop_notes }}">
                                </td>
                                <td><input type="number" name="lines[{{ $i }}][quantity]" class="form-control form-control-sm qty-input" value="{{ $line->quantity }}" min="0.0001" step="0.0001" style="width:80px" oninput="calcOrderLine({{ $i }})"></td>
                                <td><input type="number" name="lines[{{ $i }}][unit_price]" class="form-control form-control-sm price-input" value="{{ $line->unit_price }}" step="0.0001" style="width:100px" oninput="calcOrderLine({{ $i }})"></td>
                                <td><input type="date" name="lines[{{ $i }}][due_date]" class="form-control form-control-sm datepicker" value="{{ $line->due_date?->format('Y-m-d') }}" style="width:130px"></td>
                                <td style="text-align:right"><span class="line-total mono" style="font-weight:600">${{ number_format($line->line_total,2) }}</span></td>
                                <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
                            </tr>
                            @endforeach
                        @else
                        <tr><td colspan="6" style="text-align:center;padding:20px;color:var(--text-muted)">
                            No lines yet. <button type="button" class="btn btn-primary btn-sm ms-2" onclick="addOrderLine()"><i class="fa-solid fa-plus"></i> Add Line</button>
                        </td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Shipping --}}
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-truck" style="color:var(--success)"></i> Shipping</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Ship To Name</label>
                        <input type="text" name="ship_to_name" class="form-control" value="{{ old('ship_to_name',$order->ship_to_name) }}"></div>
                    <div class="col-12"><label class="form-label">Address</label>
                        <input type="text" name="ship_to_address1" class="form-control" value="{{ old('ship_to_address1',$order->ship_to_address1) }}" placeholder="Address line 1..."></div>
                    <div class="col-6"><label class="form-label">City</label>
                        <input type="text" name="ship_to_city" class="form-control" value="{{ old('ship_to_city',$order->ship_to_city) }}"></div>
                    <div class="col-3"><label class="form-label">State</label>
                        <input type="text" name="ship_to_state" class="form-control" value="{{ old('ship_to_state',$order->ship_to_state) }}"></div>
                    <div class="col-3"><label class="form-label">Zip</label>
                        <input type="text" name="ship_to_zip" class="form-control" value="{{ old('ship_to_zip',$order->ship_to_zip) }}"></div>
                    <div class="col-12"><label class="form-label">Ship Via</label>
                        <input type="text" name="ship_via" class="form-control" value="{{ old('ship_via',$order->ship_via) }}" placeholder="UPS Ground, FedEx..."></div>
                </div>
            </div>
        </div>

        {{-- Totals --}}
        <div class="card" style="position:sticky;top:80px">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-calculator" style="color:var(--success)"></i> Order Total</div></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:var(--text-muted)">Subtotal</span><span class="mono" id="ord_subtotal">$0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:var(--text-muted)">Shipping</span>
                        <input type="number" name="shipping_cost" id="ord_shipping" class="form-control form-control-sm" value="{{ old('shipping_cost',$order->shipping_cost??0) }}" style="width:100px;text-align:right" step="0.01" oninput="calcOrderTotals()">
                    </div>
                    <div style="height:1px;background:var(--border)"></div>
                    <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:700">
                        <span>Total</span><span class="mono" id="ord_total" style="color:var(--accent)">$0.00</span>
                    </div>
                </div>
                <input type="hidden" name="subtotal" id="ord_subtotal_hidden">
                <input type="hidden" name="total" id="ord_total_hidden">
                <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save Order</button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
let ordLineIndex = {{ isset($order) ? max($order->lines->count(), 1) : 0 }};
const partsData = @json($parts ?? []);

function addOrderLine() {
    const i = ordLineIndex++;
    const opts = partsData.map(p => `<option value="${p.id}" data-price="${p.unit_price}">${p.part_number} — ${p.description}</option>`).join('');
    const row = `<tr class="line-row" data-index="${i}">
        <td><select name="lines[${i}][part_id]" class="form-select form-select-sm" onchange="fillOrderPrice(this,${i})" style="min-width:200px">
            <option value="">— Select Part —</option>${opts}</select></td>
        <td><input type="number" name="lines[${i}][quantity]" class="form-control form-control-sm qty-input" value="1" min="0.0001" step="0.0001" style="width:80px" oninput="calcOrderLine(${i})"></td>
        <td><input type="number" name="lines[${i}][unit_price]" class="form-control form-control-sm price-input" value="0" step="0.0001" style="width:100px" oninput="calcOrderLine(${i})"></td>
        <td><input type="date" name="lines[${i}][due_date]" class="form-control form-control-sm datepicker" style="width:130px"></td>
        <td style="text-align:right"><span class="line-total mono" style="font-weight:600">$0.00</span></td>
        <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
    </tr>`;
    const tbody = document.getElementById('orderLinesBody');
    const empty = tbody.querySelector('td[colspan]');
    if (empty) empty.closest('tr').remove();
    tbody.insertAdjacentHTML('beforeend', row);
    flatpickr('.datepicker', { dateFormat: 'Y-m-d', allowInput: true });
}

function fillOrderPrice(sel, i) {
    const price = sel.options[sel.selectedIndex].getAttribute('data-price') || 0;
    document.querySelector(`[data-index="${i}"] .price-input`).value = price;
    calcOrderLine(i);
}

function removeLine(btn) { btn.closest('tr').remove(); calcOrderTotals(); }

function calcOrderLine(i) {
    const row = document.querySelector(`[data-index="${i}"]`);
    if (!row) return;
    const total = (parseFloat(row.querySelector('.qty-input').value)||0) * (parseFloat(row.querySelector('.price-input').value)||0);
    row.querySelector('.line-total').textContent = '$' + total.toFixed(2);
    calcOrderTotals();
}

function calcOrderTotals() {
    let sub = 0;
    document.querySelectorAll('.line-total').forEach(el => sub += parseFloat(el.textContent.replace('$',''))||0);
    const ship = parseFloat(document.getElementById('ord_shipping').value)||0;
    const total = sub + ship;
    document.getElementById('ord_subtotal').textContent = '$' + sub.toFixed(2);
    document.getElementById('ord_total').textContent = '$' + total.toFixed(2);
    document.getElementById('ord_subtotal_hidden').value = sub.toFixed(2);
    document.getElementById('ord_total_hidden').value = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => calcOrderTotals());
</script>
@endpush

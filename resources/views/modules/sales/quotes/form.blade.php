@extends('layouts.app')
@section('title', isset($quote->id) ? 'Edit Quote' : 'New Quote')
@section('breadcrumb')
    <a href="{{ route('sales.quotes.index') }}" style="color:var(--text-muted);text-decoration:none">Sales / Quotes</a> /
    <span class="current">{{ isset($quote->id) ? $quote->quote_number : 'New Quote' }}</span>
@endsection

@section('content')
<form method="POST" action="{{ isset($quote->id) ? route('sales.quotes.update',$quote) : route('sales.quotes.store') }}" id="quoteForm">
@csrf
@if(isset($quote->id)) @method('PUT') @endif

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-{{ isset($quote->id) ? 'pen' : 'plus' }}" style="color:var(--accent);margin-right:10px"></i>{{ isset($quote->id) ? 'Edit: '.$quote->quote_number : 'New Quote' }}</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.quotes.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Quote</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Header --}}
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--accent)"></i> Quote Details</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Customer <span style="color:var(--danger)">*</span></label>
                        <select name="customer_id" class="form-select select2" required>
                            <option value="">— Select Customer —</option>
                            @foreach($customers ?? [] as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $quote->customer_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quote Date <span style="color:var(--danger)">*</span></label>
                        <input type="date" name="quote_date" class="form-control datepicker" value="{{ old('quote_date', $quote->quote_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control datepicker" value="{{ old('expiry_date', $quote->expiry_date?->format('Y-m-d') ?? now()->addDays(30)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['draft','sent','won','lost','expired'] as $s)
                            <option value="{{ $s }}" {{ old('status',$quote->status??'draft')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Terms</label>
                        <select name="payment_terms" class="form-select">
                            @foreach(['Net 15','Net 30','Net 45','Net 60','COD','Prepay'] as $t)
                            <option value="{{ $t }}" {{ old('payment_terms',$quote->payment_terms??'Net 30')===$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Probability (%)</label>
                        <input type="number" name="probability" class="form-control" min="0" max="100" value="{{ old('probability',$quote->probability??50) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Customer PO #</label>
                        <input type="text" name="customer_po" class="form-control" value="{{ old('customer_po',$quote->customer_po) }}" placeholder="Customer's PO number...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Currency</label>
                        <select name="currency" class="form-select">
                            @foreach(['USD','EUR','GBP','IDR'] as $c)
                            <option value="{{ $c }}" {{ old('currency',$quote->currency??'USD')===$c?'selected':'' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ship Via</label>
                        <input type="text" name="ship_via" class="form-control" value="{{ old('ship_via',$quote->ship_via) }}" placeholder="UPS Ground...">
                    </div>
                </div>
            </div>
        </div>

        {{-- Line Items --}}
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Line Items</div>
                <button type="button" class="btn btn-primary btn-sm" onclick="addLine()"><i class="fa-solid fa-plus"></i> Add Line</button>
            </div>
            <div style="overflow-x:auto">
                <table class="erp-table" style="width:100%" id="linesTable">
                    <thead><tr><th style="width:40%">Part / Description</th><th>Qty</th><th>Unit Price</th><th>Disc %</th><th style="text-align:right">Total</th><th style="width:50px"></th></tr></thead>
                    <tbody id="linesBody">
                        @if(isset($quote) && $quote->lines->count())
                            @foreach($quote->lines as $i => $line)
                            <tr class="line-row" data-index="{{ $i }}">
                                <td><input type="hidden" name="lines[{{ $i }}][id]" value="{{ $line->id }}">
                                    <select name="lines[{{ $i }}][part_id]" class="form-select form-select-sm select2" style="min-width:200px">
                                        <option value="{{ $line->part_id }}" selected>{{ $line->part->part_number }} — {{ $line->part->description }}</option>
                                    </select>
                                </td>
                                <td><input type="number" name="lines[{{ $i }}][quantity]" class="form-control form-control-sm qty-input" value="{{ $line->quantity }}" min="1" step="0.0001" style="width:80px" oninput="calcLine({{ $i }})"></td>
                                <td><input type="number" name="lines[{{ $i }}][unit_price]" class="form-control form-control-sm price-input" value="{{ $line->unit_price }}" step="0.01" style="width:100px" oninput="calcLine({{ $i }})"></td>
                                <td><input type="number" name="lines[{{ $i }}][discount_pct]" class="form-control form-control-sm disc-input" value="{{ $line->discount_pct * 100 }}" min="0" max="100" style="width:70px" oninput="calcLine({{ $i }})"></td>
                                <td style="text-align:right"><span class="line-total mono" style="font-weight:600">${{ number_format($line->line_total,2) }}</span></td>
                                <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
                            </tr>
                            @endforeach
                        @else
                        <tr class="line-row" data-index="0">
                            <td><select name="lines[0][part_id]" class="form-select form-select-sm select2" style="min-width:200px">
                                <option value="">— Select Part —</option>
                                @foreach($parts ?? [] as $p)
                                <option value="{{ $p->id }}" data-price="{{ $p->unit_price }}">{{ $p->part_number }} — {{ $p->description }}</option>
                                @endforeach
                            </select></td>
                            <td><input type="number" name="lines[0][quantity]" class="form-control form-control-sm qty-input" value="1" min="1" step="0.0001" style="width:80px" oninput="calcLine(0)"></td>
                            <td><input type="number" name="lines[0][unit_price]" class="form-control form-control-sm price-input" value="0" step="0.01" style="width:100px" oninput="calcLine(0)"></td>
                            <td><input type="number" name="lines[0][discount_pct]" class="form-control form-control-sm disc-input" value="0" min="0" max="100" style="width:70px" oninput="calcLine(0)"></td>
                            <td style="text-align:right"><span class="line-total mono" style="font-weight:600">$0.00</span></td>
                            <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Notes --}}
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-note-sticky" style="color:var(--info)"></i> Notes</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Customer Notes (visible on PDF)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Notes for the customer...">{{ old('notes',$quote->notes ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Internal Notes</label>
                        <textarea name="internal_notes" class="form-control" rows="3" placeholder="Internal use only...">{{ old('internal_notes',$quote->internal_notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Totals --}}
    <div class="col-lg-4">
        <div class="card mb-4" style="position:sticky;top:80px">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-calculator" style="color:var(--success)"></i> Summary</div></div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px">
                    @foreach([['Subtotal','subtotal'],['Shipping','shipping_cost'],['Tax Amount','tax_amount']] as [$label,$field])
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:var(--text-muted)">{{ $label }}</span>
                        <span class="mono" id="display_{{ $field }}">$0.00</span>
                    </div>
                    @endforeach
                    <div style="height:1px;background:var(--border);margin:4px 0"></div>
                    <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:700">
                        <span>Total</span>
                        <span class="mono" id="display_total" style="color:var(--accent)">$0.00</span>
                    </div>
                </div>
                <div style="height:1px;background:var(--border);margin:16px 0"></div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Tax Rate (%)</label>
                        <input type="number" name="tax_rate_pct" id="taxRatePct" class="form-control" value="{{ old('tax_rate', isset($quote) ? $quote->tax_rate*100 : 0) }}" step="0.01" min="0" max="100" oninput="calcTotals()">
                        <input type="hidden" name="tax_rate" id="taxRate" value="{{ old('tax_rate', $quote->tax_rate ?? 0) }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Shipping ($)</label>
                        <input type="number" name="shipping_cost" id="shippingCost" class="form-control" value="{{ old('shipping_cost', $quote->shipping_cost ?? 0) }}" step="0.01" min="0" oninput="calcTotals()">
                    </div>
                </div>
                <input type="hidden" name="subtotal" id="subtotalHidden">
                <input type="hidden" name="tax_amount" id="taxAmountHidden">
                <input type="hidden" name="total" id="totalHidden">
                <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save Quote</button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
let lineIndex = {{ isset($quote) ? $quote->lines->count() : 1 }};
const partsData = @json($parts ?? []);

function addLine() {
    const i = lineIndex++;
    const opts = partsData.map(p => `<option value="${p.id}" data-price="${p.unit_price}">${p.part_number} — ${p.description}</option>`).join('');
    const row = `<tr class="line-row" data-index="${i}">
        <td><select name="lines[${i}][part_id]" class="form-select form-select-sm" onchange="fillPrice(this,${i})">
            <option value="">— Select Part —</option>${opts}</select></td>
        <td><input type="number" name="lines[${i}][quantity]" class="form-control form-control-sm qty-input" value="1" min="1" step="0.0001" style="width:80px" oninput="calcLine(${i})"></td>
        <td><input type="number" name="lines[${i}][unit_price]" class="form-control form-control-sm price-input" value="0" step="0.01" style="width:100px" oninput="calcLine(${i})"></td>
        <td><input type="number" name="lines[${i}][discount_pct]" class="form-control form-control-sm disc-input" value="0" min="0" max="100" style="width:70px" oninput="calcLine(${i})"></td>
        <td style="text-align:right"><span class="line-total mono" style="font-weight:600">$0.00</span></td>
        <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
    </tr>`;
    document.getElementById('linesBody').insertAdjacentHTML('beforeend', row);
    calcTotals();
}

function fillPrice(sel, i) {
    const opt = sel.options[sel.selectedIndex];
    const price = opt.getAttribute('data-price') || 0;
    const row = document.querySelector(`[data-index="${i}"]`);
    row.querySelector('.price-input').value = price;
    calcLine(i);
}

function removeLine(btn) {
    btn.closest('tr').remove();
    calcTotals();
}

function calcLine(i) {
    const row = document.querySelector(`[data-index="${i}"]`);
    if (!row) return;
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const disc = parseFloat(row.querySelector('.disc-input').value) || 0;
    const total = qty * price * (1 - disc/100);
    row.querySelector('.line-total').textContent = '$' + total.toFixed(2);
    calcTotals();
}

function calcTotals() {
    let subtotal = 0;
    document.querySelectorAll('.line-total').forEach(el => {
        subtotal += parseFloat(el.textContent.replace('$','')) || 0;
    });
    const shipping = parseFloat(document.getElementById('shippingCost').value) || 0;
    const taxPct = parseFloat(document.getElementById('taxRatePct').value) || 0;
    const tax = subtotal * taxPct / 100;
    const total = subtotal + shipping + tax;

    document.getElementById('display_subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('display_shipping_cost').textContent = '$' + shipping.toFixed(2);
    document.getElementById('display_tax_amount').textContent = '$' + tax.toFixed(2);
    document.getElementById('display_total').textContent = '$' + total.toFixed(2);
    document.getElementById('subtotalHidden').value = subtotal.toFixed(2);
    document.getElementById('taxAmountHidden').value = tax.toFixed(2);
    document.getElementById('taxRate').value = (taxPct/100).toFixed(4);
    document.getElementById('totalHidden').value = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => calcTotals());
</script>
@endpush

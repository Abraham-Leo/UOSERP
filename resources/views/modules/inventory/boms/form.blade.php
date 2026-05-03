@extends('layouts.app')
@section('title', isset($bom->id) ? 'Edit BOM' : 'New BOM')
@section('breadcrumb')
    <a href="{{ route('inventory.boms.index') }}" style="color:var(--text-muted);text-decoration:none">Inventory / BOMs</a> /
    <span class="current">{{ isset($bom->id) ? 'Edit BOM' : 'New BOM' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($bom->id) ? route('inventory.boms.update',$bom) : route('inventory.boms.store') }}">
@csrf
@if(isset($bom->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($bom->id) ? 'Edit BOM' : 'New Bill of Materials' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('inventory.boms.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save BOM</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">BOM Header</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Parent Part (Finished Product) *</label>
                        <select name="parent_part_id" class="form-select select2" required>
                            <option value="">— Select Part —</option>
                            @foreach($parts ?? [] as $p)
                            <option value="{{ $p->id }}" {{ old('parent_part_id',$bom->parent_part_id ?? request('part_id'))==$p->id?'selected':'' }}>{{ $p->part_number }} — {{ $p->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label">Revision</label>
                        <input type="text" name="revision" class="form-control" value="{{ old('revision',$bom->revision ?? 'A') }}" placeholder="A, B, C..."></div>
                    <div class="col-md-3"><label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['active','inactive','draft'] as $s)
                            <option {{ old('status',$bom->status??'active')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Labor Estimate (hrs)</label>
                        <input type="number" name="labor_estimate_hours" class="form-control" value="{{ old('labor_estimate_hours',$bom->labor_estimate_hours ?? 0) }}" step="0.01" min="0"></div>
                    <div class="col-md-4"><label class="form-label">Overhead Rate ($/hr)</label>
                        <input type="number" name="overhead_rate" class="form-control" value="{{ old('overhead_rate',$bom->overhead_rate ?? 0) }}" step="0.01" min="0"></div>
                    <div class="col-md-4"><label class="form-label">Effective Date</label>
                        <input type="date" name="effective_date" class="form-control datepicker" value="{{ old('effective_date',$bom->effective_date?->format('Y-m-d')) }}"></div>
                    <div class="col-12">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                            <input type="hidden" name="is_current" value="0">
                            <input type="checkbox" name="is_current" value="1" {{ old('is_current',$bom->is_current ?? true)?'checked':'' }} style="width:18px;height:18px;accent-color:var(--accent)">
                            <span style="font-size:13.5px;font-weight:500">Set as Current Revision</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">Components</div>
                <button type="button" class="btn btn-primary btn-sm" onclick="addBomLine()"><i class="fa-solid fa-plus"></i> Add Component</button>
            </div>
            <div style="overflow-x:auto">
                <table class="erp-table" style="width:100%">
                    <thead><tr><th>Part</th><th>Qty Per</th><th>UOM</th><th>Sort</th><th>Phantom</th><th>Notes</th><th></th></tr></thead>
                    <tbody id="bomLinesBody">
                        @if(isset($bom) && $bom->lines->count())
                            @foreach($bom->lines->sortBy('sort_order') as $i => $line)
                            <tr class="line-row" data-index="{{ $i }}">
                                <td><input type="hidden" name="lines[{{ $i }}][id]" value="{{ $line->id }}">
                                    <div style="font-weight:500;font-size:13px">{{ $line->part->description }}</div>
                                    <div style="font-family:monospace;font-size:11.5px;color:var(--accent)">{{ $line->part->part_number }}</div>
                                    <input type="hidden" name="lines[{{ $i }}][part_id]" value="{{ $line->part_id }}"></td>
                                <td><input type="number" name="lines[{{ $i }}][quantity]" class="form-control form-control-sm" value="{{ $line->quantity }}" min="0.0001" step="0.0001" style="width:90px"></td>
                                <td><select name="lines[{{ $i }}][unit_of_measure]" class="form-select form-select-sm" style="width:80px">
                                    @foreach(['EA','FT','IN','M','KG','LB','SQFT'] as $u)<option {{ $line->unit_of_measure===$u?'selected':'' }}>{{ $u }}</option>@endforeach
                                </select></td>
                                <td><input type="number" name="lines[{{ $i }}][sort_order]" class="form-control form-control-sm" value="{{ $line->sort_order }}" style="width:60px"></td>
                                <td><input type="checkbox" name="lines[{{ $i }}][is_phantom]" value="1" {{ $line->is_phantom?'checked':'' }} style="width:18px;height:18px;accent-color:var(--accent)"></td>
                                <td><input type="text" name="lines[{{ $i }}][notes]" class="form-control form-control-sm" value="{{ $line->notes }}" placeholder="Notes..."></td>
                                <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
                            </tr>
                            @endforeach
                        @else
                        <tr><td colspan="7" style="text-align:center;padding:20px;color:var(--text-muted)">No components. <button type="button" class="btn btn-primary btn-sm ms-2" onclick="addBomLine()">Add Component</button></td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card" style="position:sticky;top:80px">
            <div class="card-header"><div class="card-title">BOM Summary</div></div>
            <div class="card-body">
                <div style="margin-bottom:16px">
                    <div style="font-size:13px;color:var(--text-muted)">Est. Material Cost</div>
                    <div id="bomCost" style="font-size:22px;font-weight:700;color:var(--accent)">$0.00</div>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save BOM</button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
let bomIdx = {{ isset($bom) ? $bom->lines->count() : 0 }};
const partsData = @json($parts ?? []);
function addBomLine() {
    const i = bomIdx++;
    const opts = partsData.map(p => `<option value="${p.id}" data-cost="${p.standard_cost}">${p.part_number} — ${p.description}</option>`).join('');
    const uoms = ['EA','FT','IN','M','KG','LB','SQFT'].map(u => `<option>${u}</option>`).join('');
    const row = `<tr class="line-row" data-index="${i}">
        <td><select name="lines[${i}][part_id]" class="form-select form-select-sm" style="min-width:200px"><option value="">— Select —</option>${opts}</select></td>
        <td><input type="number" name="lines[${i}][quantity]" class="form-control form-control-sm" value="1" min="0.0001" step="0.0001" style="width:90px"></td>
        <td><select name="lines[${i}][unit_of_measure]" class="form-select form-select-sm" style="width:80px">${uoms}</select></td>
        <td><input type="number" name="lines[${i}][sort_order]" class="form-control form-control-sm" value="${i*10}" style="width:60px"></td>
        <td><input type="checkbox" name="lines[${i}][is_phantom]" value="1" style="width:18px;height:18px;accent-color:var(--accent)"></td>
        <td><input type="text" name="lines[${i}][notes]" class="form-control form-control-sm" placeholder="Notes..."></td>
        <td><button type="button" class="btn btn-secondary btn-sm btn-icon" onclick="removeLine(this)"><i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i></button></td>
    </tr>`;
    const tbody = document.getElementById('bomLinesBody');
    const empty = tbody.querySelector('td[colspan]');
    if (empty) empty.closest('tr').remove();
    tbody.insertAdjacentHTML('beforeend', row);
}
function removeLine(btn) { btn.closest('tr').remove(); }
</script>
@endpush

@extends('layouts.app')
@section('title', isset($part->id) ? 'Edit Part' : 'New Part')
@section('breadcrumb')
    <a href="{{ route('inventory.parts.index') }}" style="color:var(--text-muted);text-decoration:none">Inventory / Parts</a> /
    <span class="current">{{ isset($part->id) ? $part->part_number : 'New Part' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($part->id) ? route('inventory.parts.update',$part) : route('inventory.parts.store') }}">
@csrf
@if(isset($part->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($part->id) ? 'Edit: '.$part->part_number : 'New Part / Item' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('inventory.parts.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Part</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Part Information</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Part Number *</label>
                        <input type="text" name="part_number" class="form-control" value="{{ old('part_number',$part->part_number ?? '') }}" required placeholder="COMP-0001"></div>
                    <div class="col-md-8"><label class="form-label">Description *</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description',$part->description ?? '') }}" required></div>
                    <div class="col-md-4"><label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            @foreach(['component'=>'Component','subassembly'=>'Subassembly','finished_good'=>'Finished Good','raw_material'=>'Raw Material','service'=>'Service Item'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('type',$part->type??'component')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Make / Buy</label>
                        <select name="make_buy" class="form-select">
                            @foreach(['buy'=>'Buy','make'=>'Make','either'=>'Either'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('make_buy',$part->make_buy??'buy')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Unit of Measure</label>
                        <select name="unit_of_measure" class="form-select">
                            @foreach(['EA','FT','IN','M','MM','KG','LB','OZ','SQFT','L','ML','LOT'] as $u)
                            <option {{ old('unit_of_measure',$part->unit_of_measure??'EA')===$u?'selected':'' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category',$part->category ?? '') }}" placeholder="Electronics, Mechanical..."></div>
                    <div class="col-md-4"><label class="form-label">Revision</label>
                        <input type="text" name="revision" class="form-control" value="{{ old('revision',$part->revision ?? '') }}" placeholder="A, B, C..."></div>
                    <div class="col-md-4"><label class="form-label">Lead Time (days)</label>
                        <input type="number" name="lead_time_days" class="form-control" value="{{ old('lead_time_days',$part->lead_time_days ?? 0) }}" min="0" step="0.1"></div>
                    <div class="col-12"><label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes',$part->notes ?? '') }}</textarea></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title">Cost &amp; Pricing</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Standard Cost</label>
                        <div class="input-group"><span class="input-group-text">$</span>
                        <input type="number" name="standard_cost" class="form-control" value="{{ old('standard_cost',$part->standard_cost ?? 0) }}" step="0.0001" min="0"></div></div>
                    <div class="col-md-3"><label class="form-label">Unit Price (Sell)</label>
                        <div class="input-group"><span class="input-group-text">$</span>
                        <input type="number" name="unit_price" class="form-control" value="{{ old('unit_price',$part->unit_price ?? 0) }}" step="0.0001" min="0"></div></div>
                    <div class="col-md-3"><label class="form-label">Reorder Point</label>
                        <input type="number" name="reorder_point" class="form-control" value="{{ old('reorder_point',$part->reorder_point ?? 0) }}" min="0" step="0.0001"></div>
                    <div class="col-md-3"><label class="form-label">Economic Order Qty</label>
                        <input type="number" name="economic_order_qty" class="form-control" value="{{ old('economic_order_qty',$part->economic_order_qty ?? 0) }}" min="0" step="0.0001"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Settings</div></div>
            <div class="card-body">
                @foreach([
                    ['is_active','Active Part','Appears in orders & lookups',$part->is_active ?? true],
                    ['is_purchaseable','Purchaseable','Can create POs',$part->is_purchaseable ?? true],
                    ['is_saleable','Saleable','Can sell to customers',$part->is_saleable ?? false],
                    ['is_manufactured','Manufactured','Has production BOM',$part->is_manufactured ?? false],
                    ['track_serial','Track Serials','Track individual serial numbers',$part->track_serial ?? false],
                    ['track_lot','Track Lot/Batch','Track lot and date codes',$part->track_lot ?? false],
                ] as [$name,$title,$desc,$checked])
                <label style="display:flex;align-items:flex-start;gap:12px;cursor:pointer;margin-bottom:14px">
                    <input type="hidden" name="{{ $name }}" value="0">
                    <input type="checkbox" name="{{ $name }}" value="1" {{ old($name,$checked)?'checked':'' }} style="width:18px;height:18px;accent-color:var(--accent);margin-top:2px;flex-shrink:0">
                    <div><div style="font-size:13.5px;font-weight:500">{{ $title }}</div>
                    <div style="font-size:11.5px;color:var(--text-muted)">{{ $desc }}</div></div>
                </label>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save Part</button>
    </div>
</div>
</form>
@endsection

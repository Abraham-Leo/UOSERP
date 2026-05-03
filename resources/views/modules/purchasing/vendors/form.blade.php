@extends('layouts.app')
@section('title', isset($vendor->id) ? 'Edit Vendor' : 'New Vendor')
@section('breadcrumb')
    <a href="{{ route('purchasing.vendors.index') }}" style="color:var(--text-muted);text-decoration:none">Purchasing / Vendors</a> /
    <span class="current">{{ isset($vendor->id) ? $vendor->name : 'New Vendor' }}</span>
@endsection
@section('content')
<form method="POST" action="{{ isset($vendor->id) ? route('purchasing.vendors.update',$vendor) : route('purchasing.vendors.store') }}">
@csrf
@if(isset($vendor->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title">{{ isset($vendor->id) ? 'Edit: '.$vendor->name : 'New Vendor' }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchasing.vendors.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Vendor</button>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--accent)"></i> Vendor Information</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Vendor Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name',$vendor->name ?? '') }}" required></div>
                    <div class="col-md-6"><label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email',$vendor->email ?? '') }}"></div>
                    <div class="col-md-4"><label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone',$vendor->phone ?? '') }}"></div>
                    <div class="col-md-4"><label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website',$vendor->website ?? '') }}" placeholder="https://"></div>
                    <div class="col-md-4"><label class="form-label">Tax ID</label>
                        <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id',$vendor->tax_id ?? '') }}"></div>
                    <div class="col-12"><label class="form-label">Billing Address</label>
                        <input type="text" name="billing_address1" class="form-control" value="{{ old('billing_address1',$vendor->billing_address1 ?? '') }}" placeholder="Street address..."></div>
                    <div class="col-md-4"><label class="form-label">City</label>
                        <input type="text" name="billing_city" class="form-control" value="{{ old('billing_city',$vendor->billing_city ?? '') }}"></div>
                    <div class="col-md-4"><label class="form-label">State</label>
                        <input type="text" name="billing_state" class="form-control" value="{{ old('billing_state',$vendor->billing_state ?? '') }}"></div>
                    <div class="col-md-4"><label class="form-label">Zip</label>
                        <input type="text" name="billing_zip" class="form-control" value="{{ old('billing_zip',$vendor->billing_zip ?? '') }}"></div>
                    <div class="col-12"><label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes',$vendor->notes ?? '') }}</textarea></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Settings</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Payment Terms</label>
                        <select name="payment_terms" class="form-select">
                            @foreach(['Net 15','Net 30','Net 45','Net 60','COD','Prepay'] as $t)
                            <option {{ old('payment_terms',$vendor->payment_terms??'Net 30')===$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Currency</label>
                        <select name="currency" class="form-select">
                            @foreach(['USD','EUR','GBP','IDR'] as $c)
                            <option {{ old('currency',$vendor->currency??'USD')===$c?'selected':'' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Minimum Order ($)</label>
                        <input type="number" name="minimum_order" class="form-control" value="{{ old('minimum_order',$vendor->minimum_order ?? 0) }}" step="0.01" min="0"></div>
                    <div class="col-12">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active',$vendor->is_active ?? true)?'checked':'' }} style="width:18px;height:18px;accent-color:var(--accent)">
                            <span style="font-size:13.5px;font-weight:500">Active Vendor</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                            <input type="hidden" name="on_hold" value="0">
                            <input type="checkbox" name="on_hold" value="1" {{ old('on_hold',$vendor->on_hold ?? false)?'checked':'' }} style="width:18px;height:18px;accent-color:var(--danger)">
                            <span style="font-size:13.5px;font-weight:500;color:var(--danger)">On Hold</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg"><i class="fa-solid fa-save"></i> Save Vendor</button>
    </div>
</div>
</form>
@endsection

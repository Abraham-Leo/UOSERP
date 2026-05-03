@extends('layouts.app')

@section('title', $action === 'create' ? 'New Customer' : 'Edit Customer')
@section('breadcrumb')
    <a href="{{ route('crm.customers.index') }}" style="color:var(--text-muted);text-decoration:none">CRM / Customers</a> /
    <span class="current">{{ $action === 'create' ? 'New Customer' : $customer->name }}</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-{{ $action === 'create' ? 'user-plus' : 'user-pen' }}" style="color:var(--accent);margin-right:10px"></i>
            {{ $action === 'create' ? 'New Customer' : 'Edit: ' . $customer->name }}
        </h1>
        <p class="page-subtitle">{{ $action === 'create' ? 'Create a new customer account' : 'Update customer information' }}</p>
    </div>
    <a href="{{ route('crm.customers.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ $action === 'create' ? route('crm.customers.store') : route('crm.customers.update', $customer) }}">
    @csrf
    @if($action === 'edit') @method('PUT') @endif

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--accent)"></i> Basic Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $customer->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control"
                                   value="{{ old('company_name', $customer->company_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $customer->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $customer->phone) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fax</label>
                            <input type="text" name="fax" class="form-control"
                                   value="{{ old('fax', $customer->fax) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control"
                                   value="{{ old('website', $customer->website) }}" placeholder="https://">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Account Type</label>
                            <select name="account_type" class="form-select">
                                <option value="customer" {{ old('account_type', $customer->account_type) === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="prospect" {{ old('account_type', $customer->account_type) === 'prospect' ? 'selected' : '' }}>Prospect</option>
                                <option value="lead" {{ old('account_type', $customer->account_type) === 'lead' ? 'selected' : '' }}>Lead</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Address -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-map-pin" style="color:var(--warning)"></i> Billing Address</div>
                    <button type="button" class="btn btn-secondary btn-sm" id="copyAddressBtn">
                        <i class="fa-solid fa-copy"></i> Copy to Shipping
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Address Line 1</label>
                            <input type="text" name="billing_address1" id="billing_address1" class="form-control"
                                   value="{{ old('billing_address1', $customer->billing_address1) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" name="billing_address2" id="billing_address2" class="form-control"
                                   value="{{ old('billing_address2', $customer->billing_address2) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="billing_city" id="billing_city" class="form-control"
                                   value="{{ old('billing_city', $customer->billing_city) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">State/Province</label>
                            <input type="text" name="billing_state" id="billing_state" class="form-control"
                                   value="{{ old('billing_state', $customer->billing_state) }}" maxlength="50">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Zip/Postal</label>
                            <input type="text" name="billing_zip" id="billing_zip" class="form-control"
                                   value="{{ old('billing_zip', $customer->billing_zip) }}" maxlength="20">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Country</label>
                            <select name="billing_country" id="billing_country" class="form-select">
                                <option value="US" {{ old('billing_country', $customer->billing_country) === 'US' ? 'selected' : '' }}>United States</option>
                                <option value="CA" {{ old('billing_country', $customer->billing_country) === 'CA' ? 'selected' : '' }}>Canada</option>
                                <option value="GB" {{ old('billing_country', $customer->billing_country) === 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="DE" {{ old('billing_country', $customer->billing_country) === 'DE' ? 'selected' : '' }}>Germany</option>
                                <option value="ID" {{ old('billing_country', $customer->billing_country) === 'ID' ? 'selected' : '' }}>Indonesia</option>
                                <option value="OTHER">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-truck" style="color:var(--success)"></i> Default Shipping Address</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Address Line 1</label>
                            <input type="text" name="shipping_address1" id="shipping_address1" class="form-control"
                                   value="{{ old('shipping_address1', $customer->shipping_address1) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="shipping_city" id="shipping_city" class="form-control"
                                   value="{{ old('shipping_city', $customer->shipping_city) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">State</label>
                            <input type="text" name="shipping_state" id="shipping_state" class="form-control"
                                   value="{{ old('shipping_state', $customer->shipping_state) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Zip</label>
                            <input type="text" name="shipping_zip" id="shipping_zip" class="form-control"
                                   value="{{ old('shipping_zip', $customer->shipping_zip) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Country</label>
                            <select name="shipping_country" id="shipping_country" class="form-select">
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="ID" {{ old('shipping_country', $customer->shipping_country) === 'ID' ? 'selected' : '' }}>Indonesia</option>
                                <option value="OTHER">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-note-sticky" style="color:var(--info)"></i> Notes / Knowledge Base</div>
                </div>
                <div class="card-body">
                    <textarea name="notes" class="form-control" rows="4" placeholder="Internal notes about this customer...">{{ old('notes', $customer->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Sales Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-sliders" style="color:var(--purple)"></i> Sales Settings</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Payment Terms <span style="color:var(--danger)">*</span></label>
                            <select name="payment_terms" class="form-select" required>
                                @foreach(['Net 15','Net 30','Net 45','Net 60','Net 90','COD','Prepay','2/10 Net 30'] as $term)
                                <option value="{{ $term }}" {{ old('payment_terms', $customer->payment_terms ?? 'Net 30') === $term ? 'selected' : '' }}>{{ $term }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Currency</label>
                            <select name="currency" class="form-select">
                                <option value="USD" {{ old('currency', $customer->currency ?? 'USD') === 'USD' ? 'selected' : '' }}>USD — US Dollar</option>
                                <option value="EUR" {{ old('currency', $customer->currency) === 'EUR' ? 'selected' : '' }}>EUR — Euro</option>
                                <option value="GBP" {{ old('currency', $customer->currency) === 'GBP' ? 'selected' : '' }}>GBP — British Pound</option>
                                <option value="IDR" {{ old('currency', $customer->currency) === 'IDR' ? 'selected' : '' }}>IDR — Indonesian Rupiah</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Default Ship Via</label>
                            <select name="ship_via" class="form-select">
                                <option value="">— Select —</option>
                                <option value="UPS Ground">UPS Ground</option>
                                <option value="UPS Air">UPS Air</option>
                                <option value="FedEx Ground">FedEx Ground</option>
                                <option value="FedEx Express">FedEx Express</option>
                                <option value="Customer Pickup">Customer Pickup</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Credit Limit ($)</label>
                            <input type="number" name="credit_limit" class="form-control"
                                   value="{{ old('credit_limit', $customer->credit_limit ?? 0) }}" step="0.01" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tax Rate (e.g. 0.0825 for 8.25%)</label>
                            <input type="number" name="tax_rate" class="form-control"
                                   value="{{ old('tax_rate', $customer->tax_rate ?? 0) }}" step="0.0001" min="0" max="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Commission Rate</label>
                            <input type="number" name="commission_rate" class="form-control"
                                   value="{{ old('commission_rate', $customer->commission_rate ?? 0) }}" step="0.0001" min="0" max="1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax / Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-toggle-on" style="color:var(--success)"></i> Status & Tax</div>
                </div>
                <div class="card-body">
                    <div style="display:flex;flex-direction:column;gap:14px">
                        <label style="display:flex;align-items:center;gap:12px;cursor:pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" style="width:18px;height:18px;accent-color:var(--accent)"
                                   {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}>
                            <div>
                                <div style="font-size:13.5px;font-weight:500">Active Customer</div>
                                <div style="font-size:11.5px;color:var(--text-muted)">Inactive customers won't appear in order dropdowns</div>
                            </div>
                        </label>
                        <label style="display:flex;align-items:center;gap:12px;cursor:pointer">
                            <input type="hidden" name="taxable" value="0">
                            <input type="checkbox" name="taxable" value="1" style="width:18px;height:18px;accent-color:var(--accent)"
                                   {{ old('taxable', $customer->taxable ?? true) ? 'checked' : '' }}>
                            <div>
                                <div style="font-size:13.5px;font-weight:500">Taxable</div>
                                <div style="font-size:11.5px;color:var(--text-muted)">Apply sales tax on invoices</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="d-flex flex-column gap-2">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fa-solid fa-{{ $action === 'create' ? 'plus' : 'save' }}"></i>
                    {{ $action === 'create' ? 'Create Customer' : 'Save Changes' }}
                </button>
                <a href="{{ route('crm.customers.index') }}" class="btn btn-secondary w-100">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('copyAddressBtn')?.addEventListener('click', function() {
    const fields = ['address1','address2','city','state','zip','country'];
    fields.forEach(f => {
        const billing = document.getElementById('billing_' + f);
        const shipping = document.getElementById('shipping_' + f);
        if (billing && shipping) {
            if (shipping.tagName === 'SELECT') {
                const opt = [...shipping.options].find(o => o.value === billing.value);
                if (opt) opt.selected = true;
            } else {
                shipping.value = billing.value;
            }
        }
    });
    showToast('Shipping address copied from billing', 'success');
});
</script>
@endpush

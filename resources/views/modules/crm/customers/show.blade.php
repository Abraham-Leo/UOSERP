@extends('layouts.app')
@section('title', $customer->name)
@section('breadcrumb')
    <a href="{{ route('crm.customers.index') }}" style="color:var(--text-muted);text-decoration:none">CRM / Customers</a> /
    <span class="current">{{ $customer->name }}</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div style="display:flex;align-items:center;gap:14px">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;color:#fff;font-family:'Sora',sans-serif;">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="page-title">{{ $customer->name }}</h1>
                <div style="display:flex;align-items:center;gap:8px;margin-top:4px">
                    <span class="mono" style="color:var(--text-muted);font-size:12px">{{ $customer->customer_number }}</span>
                    @if($customer->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                    <span class="badge badge-info">{{ ucfirst($customer->account_type) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.orders.create') }}?customer_id={{ $customer->id }}" class="btn btn-secondary">
            <i class="fa-solid fa-plus"></i> New Order
        </a>
        <a href="{{ route('crm.customers.edit', $customer) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen"></i> Edit
        </a>
        <div class="dropdown">
            <button class="btn btn-secondary" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu" style="font-size:13px">
                <li><a class="dropdown-item" href="{{ route('crm.customers.statement', $customer) }}"><i class="fa-solid fa-file-invoice me-2"></i>A/R Statement</a></li>
                <li><a class="dropdown-item" href="{{ route('sales.quotes.create') }}?customer_id={{ $customer->id }}"><i class="fa-solid fa-file-invoice-dollar me-2"></i>New Quote</a></li>
                <li><a class="dropdown-item" href="{{ route('shipping.rma.create') }}?customer_id={{ $customer->id }}"><i class="fa-solid fa-rotate-left me-2"></i>New RMA</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('crm.customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-trash me-2"></i>Delete</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- KPI Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card accent">
            <div class="stat-icon accent"><i class="fa-solid fa-cart-flatbed"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fa-solid fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['open_orders'] }}</div>
                <div class="stat-label">Open Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fa-solid fa-dollar-sign"></i></div>
            <div class="stat-content">
                <div class="stat-value">${{ number_format($stats['ytd_revenue'], 0) }}</div>
                <div class="stat-label">YTD Revenue</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div class="stat-content">
                <div class="stat-value">${{ number_format($stats['ar_balance'], 0) }}</div>
                <div class="stat-label">A/R Balance</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT: Info --}}
    <div class="col-lg-4">
        {{-- Contact Info --}}
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-address-card" style="color:var(--accent)"></i> Contact Info</div></div>
            <div class="card-body">
                @if($customer->email)
                <div style="display:flex;gap:10px;margin-bottom:12px">
                    <i class="fa-solid fa-envelope" style="color:var(--text-muted);margin-top:2px;width:16px"></i>
                    <a href="mailto:{{ $customer->email }}" style="color:var(--accent)">{{ $customer->email }}</a>
                </div>
                @endif
                @if($customer->phone)
                <div style="display:flex;gap:10px;margin-bottom:12px">
                    <i class="fa-solid fa-phone" style="color:var(--text-muted);margin-top:2px;width:16px"></i>
                    <span>{{ $customer->phone }}</span>
                </div>
                @endif
                @if($customer->website)
                <div style="display:flex;gap:10px;margin-bottom:12px">
                    <i class="fa-solid fa-globe" style="color:var(--text-muted);margin-top:2px;width:16px"></i>
                    <a href="{{ $customer->website }}" target="_blank" style="color:var(--accent)">{{ $customer->website }}</a>
                </div>
                @endif
            </div>
        </div>

        {{-- Billing Address --}}
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-map-pin" style="color:var(--warning)"></i> Billing Address</div></div>
            <div class="card-body" style="font-size:13.5px;line-height:1.8;color:var(--text-muted)">
                {{ $customer->billing_address1 ?? '—' }}<br>
                @if($customer->billing_address2) {{ $customer->billing_address2 }}<br> @endif
                {{ implode(', ', array_filter([$customer->billing_city, $customer->billing_state, $customer->billing_zip])) }}<br>
                {{ $customer->billing_country }}
            </div>
        </div>

        {{-- Sales Settings --}}
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-sliders" style="color:var(--purple)"></i> Sales Settings</div></div>
            <div class="card-body">
                @foreach([
                    ['Terms', $customer->payment_terms],
                    ['Currency', $customer->currency],
                    ['Ship Via', $customer->ship_via ?? '—'],
                    ['Credit Limit', '$'.number_format($customer->credit_limit, 0)],
                    ['Tax Rate', number_format($customer->tax_rate * 100, 2).'%'],
                    ['Taxable', $customer->taxable ? 'Yes' : 'No'],
                ] as [$label, $val])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $label }}</span>
                    <span style="font-weight:500">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        @if($customer->notes)
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-note-sticky" style="color:var(--info)"></i> Notes</div></div>
            <div class="card-body" style="font-size:13.5px;color:var(--text-muted);line-height:1.7">{{ $customer->notes }}</div>
        </div>
        @endif
    </div>

    {{-- RIGHT: Tabs --}}
    <div class="col-lg-8">
        <div class="card" style="overflow:hidden">
            <ul class="nav nav-tabs" style="padding:0 16px;border-bottom:1px solid var(--border)">
                <li class="nav-item"><a class="nav-link active" href="#orders" data-bs-toggle="tab">Orders <span class="badge badge-primary">{{ $stats['total_orders'] }}</span></a></li>
                <li class="nav-item"><a class="nav-link" href="#quotes" data-bs-toggle="tab">Quotes</a></li>
                <li class="nav-item"><a class="nav-link" href="#invoices" data-bs-toggle="tab">Invoices</a></li>
                <li class="nav-item"><a class="nav-link" href="#contacts" data-bs-toggle="tab">Contacts</a></li>
            </ul>
            <div class="tab-content">
                {{-- Orders Tab --}}
                <div class="tab-pane fade show active" id="orders">
                    <table class="erp-table" style="width:100%">
                        <thead><tr><th>Order #</th><th>Type</th><th>Status</th><th>Total</th><th>Due Date</th><th></th></tr></thead>
                        <tbody>
                            @forelse($customer->orders->take(10) as $order)
                            <tr>
                                <td><a href="{{ route('sales.orders.show', $order) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px;font-weight:600">{{ $order->order_number }}</a></td>
                                <td><span class="badge badge-info" style="font-size:10px">{{ ucfirst($order->type) }}</span></td>
                                <td><x-status-badge :status="$order->status" /></td>
                                <td style="font-weight:600">${{ number_format($order->total, 0) }}</td>
                                <td style="font-size:12.5px;color:var(--text-muted)">{{ $order->due_date?->format('M d, Y') ?? '—' }}</td>
                                <td><a href="{{ route('sales.orders.show', $order) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted)">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Quotes Tab --}}
                <div class="tab-pane fade" id="quotes">
                    <table class="erp-table" style="width:100%">
                        <thead><tr><th>Quote #</th><th>Status</th><th>Total</th><th>Expiry</th><th>Prob.</th><th></th></tr></thead>
                        <tbody>
                            @forelse($customer->quotes->take(10) as $quote)
                            <tr>
                                <td><a href="{{ route('sales.quotes.show', $quote) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px;font-weight:600">{{ $quote->quote_number }}</a></td>
                                <td><x-status-badge :status="$quote->status" /></td>
                                <td style="font-weight:600">${{ number_format($quote->total, 0) }}</td>
                                <td style="font-size:12.5px;color:var(--text-muted)">{{ $quote->expiry_date?->format('M d, Y') ?? '—' }}</td>
                                <td>{{ $quote->probability }}%</td>
                                <td><a href="{{ route('sales.quotes.show', $quote) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted)">No quotes yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Invoices Tab --}}
                <div class="tab-pane fade" id="invoices">
                    <table class="erp-table" style="width:100%">
                        <thead><tr><th>Invoice #</th><th>Status</th><th>Total</th><th>Balance Due</th><th>Due Date</th><th></th></tr></thead>
                        <tbody>
                            @forelse($customer->invoices->take(10) as $inv)
                            <tr>
                                <td><a href="{{ route('sales.invoices.show', $inv) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px;font-weight:600">{{ $inv->invoice_number }}</a></td>
                                <td><x-status-badge :status="$inv->status" /></td>
                                <td style="font-weight:600">${{ number_format($inv->total, 2) }}</td>
                                <td style="font-weight:600;color:{{ $inv->balance_due > 0 ? 'var(--danger)' : 'var(--success)' }}">${{ number_format($inv->balance_due, 2) }}</td>
                                <td style="font-size:12.5px;color:var(--text-muted)">{{ $inv->due_date?->format('M d, Y') ?? '—' }}</td>
                                <td><a href="{{ route('sales.invoices.show', $inv) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted)">No invoices yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Contacts Tab --}}
                <div class="tab-pane fade" id="contacts">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addContactModal">
                                <i class="fa-solid fa-plus"></i> Add Contact
                            </button>
                        </div>
                        @forelse($customer->contacts as $contact)
                        <div style="display:flex;align-items:center;gap:14px;padding:12px;background:var(--bg);border-radius:8px;margin-bottom:8px">
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--accent)">{{ strtoupper(substr($contact->name,0,1)) }}</div>
                            <div style="flex:1">
                                <div style="font-weight:600;font-size:13.5px">{{ $contact->name }}
                                    @if($contact->primary_contact) <span class="badge badge-success" style="font-size:10px;margin-left:4px">Primary</span> @endif
                                </div>
                                <div style="font-size:12px;color:var(--text-muted)">{{ $contact->title }} {{ $contact->email ? '· '.$contact->email : '' }}</div>
                            </div>
                            <span style="font-size:12.5px;color:var(--text-muted)">{{ $contact->phone }}</span>
                        </div>
                        @empty
                        <div style="text-align:center;padding:32px;color:var(--text-muted)">No contacts added yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

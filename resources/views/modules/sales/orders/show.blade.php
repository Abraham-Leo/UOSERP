@extends('layouts.app')
@section('title', $order->order_number)
@section('breadcrumb')
    <a href="{{ route('sales.orders.index') }}" style="color:var(--text-muted);text-decoration:none">Sales / Orders</a> /
    <span class="current">{{ $order->order_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $order->order_number }}</h1>
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
            <x-status-badge :status="$order->status" />
            <span class="badge badge-info">{{ ucfirst(str_replace('_',' ',$order->type)) }}</span>
            <span style="font-size:13px;color:var(--text-muted)">{{ $order->customer->name }}</span>
            @if($order->due_date)
            <span style="font-size:13px;color:{{ $order->due_date->isPast() && !in_array($order->status,['invoiced','cancelled']) ? 'var(--danger)' : 'var(--text-muted)' }}">
                · Due {{ $order->due_date->format('M d, Y') }}
                @if($order->due_date->isPast() && !in_array($order->status,['invoiced','cancelled'])) <i class="fa-solid fa-fire" style="color:var(--danger)"></i> @endif
            </span>
            @endif
        </div>
    </div>
    <div class="d-flex gap-2">
        @if(!$order->released && !in_array($order->status,['invoiced','cancelled']))
        <form method="POST" action="{{ route('sales.orders.release',$order) }}">
            @csrf
            <button type="submit" class="btn btn-warning"><i class="fa-solid fa-play"></i> Release to Production</button>
        </form>
        @endif
        <a href="{{ route('sales.orders.pdf',$order) }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-file-pdf"></i> PDF</a>
        <a href="{{ route('sales.orders.edit',$order) }}" class="btn btn-secondary"><i class="fa-solid fa-pen"></i> Edit</a>
        <div class="dropdown">
            <button class="btn btn-secondary" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu" style="font-size:13px">
                <li><a class="dropdown-item" href="{{ route('shipping.shipments.create') }}?order_id={{ $order->id }}"><i class="fa-solid fa-truck-fast me-2"></i>Create Shipment</a></li>
                <li><a class="dropdown-item" href="{{ route('sales.invoices.create') }}?order_id={{ $order->id }}"><i class="fa-solid fa-receipt me-2"></i>Create Invoice</a></li>
                <li><a class="dropdown-item" href="{{ route('production.work-orders.create') }}?order_id={{ $order->id }}"><i class="fa-solid fa-gears me-2"></i>Create Work Order</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('sales.orders.destroy',$order) }}" onsubmit="return confirm('Delete this order?')">
                        @csrf @method('DELETE')
                        <button class="dropdown-item text-danger"><i class="fa-solid fa-trash me-2"></i>Delete</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Status Banner if released --}}
@if($order->released)
<div class="alert alert-success mb-4">
    <i class="fa-solid fa-check-circle"></i>
    Released to production on {{ $order->released_at?->format('M d, Y H:i') }}
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Line Items --}}
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Line Items</div></div>
            <table class="erp-table" style="width:100%">
                <thead>
                    <tr><th>#</th><th>Part #</th><th>Description</th><th style="text-align:right">Qty</th><th style="text-align:right">Shipped</th><th style="text-align:right">Unit Price</th><th style="text-align:right">Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($order->lines as $i => $line)
                    <tr>
                        <td style="color:var(--text-muted);font-size:12px">{{ $i+1 }}</td>
                        <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">{{ $line->part->part_number }}</td>
                        <td>
                            <div style="font-weight:500">{{ $line->part->description }}</div>
                            @if($line->shop_notes)<div style="font-size:11.5px;color:var(--warning)"><i class="fa-solid fa-triangle-exclamation"></i> {{ $line->shop_notes }}</div>@endif
                        </td>
                        <td style="text-align:right;font-family:monospace">{{ number_format($line->quantity,0) }}</td>
                        <td style="text-align:right;font-family:monospace;color:{{ $line->qty_shipped >= $line->quantity ? 'var(--success)' : 'var(--text-muted)' }}">{{ number_format($line->qty_shipped,0) }}</td>
                        <td style="text-align:right;font-family:monospace">${{ number_format($line->unit_price,4) }}</td>
                        <td style="text-align:right;font-weight:600">${{ number_format($line->line_total,2) }}</td>
                        <td><x-status-badge :status="$line->status" /></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><td colspan="6" style="text-align:right;color:var(--text-muted);font-size:13px;padding:8px 14px">Subtotal</td><td style="text-align:right;font-weight:600;padding:8px 14px">${{ number_format($order->subtotal,2) }}</td><td></td></tr>
                    @if($order->shipping_cost > 0)
                    <tr><td colspan="6" style="text-align:right;color:var(--text-muted);font-size:13px;padding:4px 14px">Shipping</td><td style="text-align:right;padding:4px 14px">${{ number_format($order->shipping_cost,2) }}</td><td></td></tr>
                    @endif
                    <tr style="background:var(--accent-soft)"><td colspan="6" style="text-align:right;font-weight:700;font-size:15px;padding:10px 14px">TOTAL</td><td style="text-align:right;font-weight:700;font-size:15px;color:var(--accent);padding:10px 14px">${{ number_format($order->total,2) }}</td><td></td></tr>
                </tfoot>
            </table>
        </div>

        {{-- Related Tabs --}}
        <div class="card">
            <ul class="nav nav-tabs" style="padding:0 16px;border-bottom:1px solid var(--border)">
                <li class="nav-item"><a class="nav-link active" href="#wo" data-bs-toggle="tab">Work Orders <span class="badge badge-secondary">{{ $order->workOrders->count() }}</span></a></li>
                <li class="nav-item"><a class="nav-link" href="#ship" data-bs-toggle="tab">Shipments <span class="badge badge-secondary">{{ $order->shipments->count() }}</span></a></li>
                <li class="nav-item"><a class="nav-link" href="#inv" data-bs-toggle="tab">Invoices <span class="badge badge-secondary">{{ $order->invoices->count() }}</span></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="wo">
                    <table class="erp-table" style="width:100%">
                        <thead><tr><th>WO #</th><th>Part</th><th>Status</th><th>Progress</th><th>Due</th><th></th></tr></thead>
                        <tbody>
                            @forelse($order->workOrders as $wo)
                            <tr>
                                <td><a href="{{ route('production.work-orders.show',$wo) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px;font-weight:600">{{ $wo->wo_number }}</a></td>
                                <td style="font-size:13px">{{ $wo->part->description }}</td>
                                <td><x-status-badge :status="$wo->status" /></td>
                                <td style="min-width:120px">
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <div style="flex:1;height:5px;background:#f1f5f9;border-radius:3px;overflow:hidden">
                                            <div style="height:100%;width:{{ $wo->progress_pct }}%;background:var(--accent);border-radius:3px"></div>
                                        </div>
                                        <span style="font-size:11px;color:var(--text-muted);font-family:monospace">{{ $wo->progress_pct }}%</span>
                                    </div>
                                </td>
                                <td style="font-size:12.5px;color:var(--text-muted)">{{ $wo->due_date?->format('M d') }}</td>
                                <td><a href="{{ route('production.work-orders.show',$wo) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No work orders.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="ship">
                    <table class="erp-table" style="width:100%">
                        <thead><tr><th>Shipment #</th><th>Status</th><th>Carrier</th><th>Tracking</th><th>Ship Date</th><th></th></tr></thead>
                        <tbody>
                            @forelse($order->shipments as $shp)
                            <tr>
                                <td><a href="{{ route('shipping.shipments.show',$shp) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px;font-weight:600">{{ $shp->shipment_number }}</a></td>
                                <td><x-status-badge :status="$shp->status" /></td>
                                <td style="font-size:13px">{{ $shp->carrier ?? '—' }}</td>
                                <td style="font-family:monospace;font-size:12px">{{ $shp->tracking_number ?? '—' }}</td>
                                <td style="font-size:12.5px;color:var(--text-muted)">{{ $shp->ship_date->format('M d, Y') }}</td>
                                <td><a href="{{ route('shipping.shipments.show',$shp) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No shipments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="inv">
                    <table class="erp-table" style="width:100%">
                        <thead><tr><th>Invoice #</th><th>Status</th><th>Total</th><th>Balance</th><th>Due</th><th></th></tr></thead>
                        <tbody>
                            @forelse($order->invoices as $inv)
                            <tr>
                                <td><a href="{{ route('sales.invoices.show',$inv) }}" style="color:var(--accent);font-family:monospace;font-size:12.5px;font-weight:600">{{ $inv->invoice_number }}</a></td>
                                <td><x-status-badge :status="$inv->status" /></td>
                                <td style="font-weight:600">${{ number_format($inv->total,2) }}</td>
                                <td style="font-weight:600;color:{{ $inv->balance_due > 0 ? 'var(--danger)' : 'var(--success)' }}">${{ number_format($inv->balance_due,2) }}</td>
                                <td style="font-size:12.5px;color:var(--text-muted)">{{ $inv->due_date?->format('M d, Y') }}</td>
                                <td><a href="{{ route('sales.invoices.show',$inv) }}" class="btn btn-secondary btn-sm btn-icon"><i class="fa-solid fa-eye" style="font-size:11px"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No invoices.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title">Order Info</div></div>
            <div class="card-body">
                @foreach([
                    ['Order #',$order->order_number],
                    ['Customer',$order->customer->name],
                    ['Type',ucfirst(str_replace('_',' ',$order->type))],
                    ['Order Date',$order->order_date->format('M d, Y')],
                    ['Due Date',$order->due_date?->format('M d, Y') ?? '—'],
                    ['Terms',$order->payment_terms],
                    ['Currency',$order->currency],
                    ['Customer PO',$order->customer_po ?? '—'],
                    ['Released',$order->released ? '✅ Yes ('.$order->released_at?->format('M d').')' : '❌ No'],
                ] as [$l,$v])
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);font-size:13px">
                    <span style="color:var(--text-muted)">{{ $l }}</span><span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-truck" style="color:var(--success)"></i> Ship To</div></div>
            <div class="card-body" style="font-size:13.5px;line-height:1.9;color:var(--text-muted)">
                {{ $order->ship_to_name ?? $order->customer->name }}<br>
                {{ $order->ship_to_address1 ?? $order->customer->shipping_address1 ?? '—' }}<br>
                {{ implode(', ', array_filter([$order->ship_to_city, $order->ship_to_state, $order->ship_to_zip])) }}<br>
                Ship Via: <strong>{{ $order->ship_via ?? '—' }}</strong>
            </div>
        </div>

        @if($order->internal_notes)
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-note-sticky" style="color:var(--warning)"></i> Internal Notes</div></div>
            <div class="card-body" style="font-size:13.5px;color:var(--text-muted);white-space:pre-line">{{ $order->internal_notes }}</div>
        </div>
        @endif
    </div>
</div>
@endsection

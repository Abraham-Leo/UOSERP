<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traveler — {{ $wo->wo_number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; background: #fff; }
        .header { border: 2px solid #000; margin-bottom: 12px; padding: 10px; display: flex; justify-content: space-between; align-items: flex-start; }
        .header h1 { font-size: 20px; font-weight: 700; }
        .header .wo-num { font-size: 28px; font-weight: 800; font-family: monospace; color: #1a56db; }
        .info-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 8px; margin-bottom: 12px; }
        .info-box { border: 1px solid #000; padding: 6px; }
        .info-box label { font-size: 9px; text-transform: uppercase; font-weight: 700; color: #555; display: block; }
        .info-box span { font-size: 13px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #f0f0f0; border: 1px solid #000; padding: 6px; font-size: 10px; text-transform: uppercase; text-align: left; }
        td { border: 1px solid #000; padding: 6px; font-size: 11px; }
        .sign-row td { height: 32px; }
        .footer { border-top: 2px solid #000; padding-top: 8px; font-size: 10px; text-align: center; color: #555; }
        @media print { @page { margin: 0.5in; } }
    </style>
</head>
<body>
<div class="header">
    <div>
        <h1>{{ config('app.name','Cetec ERP') }}</h1>
        <div style="font-size:11px;color:#555">Production Traveler / Router</div>
    </div>
    <div style="text-align:right">
        <div class="wo-num">{{ $wo->wo_number }}</div>
        <div style="font-size:11px">Printed: {{ now()->format('M d, Y H:i') }}</div>
    </div>
</div>

<div class="info-grid">
    <div class="info-box"><label>Part Number</label><span>{{ $wo->part->part_number }}</span></div>
    <div class="info-box"><label>Description</label><span style="font-size:11px">{{ $wo->part->description }}</span></div>
    <div class="info-box"><label>Quantity</label><span>{{ number_format($wo->quantity,0) }} {{ $wo->part->unit_of_measure }}</span></div>
    <div class="info-box"><label>Due Date</label><span>{{ $wo->due_date?->format('M d, Y') ?? '—' }}</span></div>
    <div class="info-box"><label>Sales Order</label><span>{{ $wo->order?->order_number ?? '—' }}</span></div>
    <div class="info-box"><label>Customer</label><span style="font-size:11px">{{ $wo->order?->customer->name ?? '—' }}</span></div>
    <div class="info-box"><label>Work Start</label><span>{{ $wo->work_start_date?->format('M d, Y') ?? '—' }}</span></div>
    <div class="info-box"><label>Status</label><span>{{ strtoupper($wo->status) }}</span></div>
</div>

<h3 style="font-size:12px;font-weight:700;margin-bottom:6px;text-transform:uppercase">Operations / Router</h3>
<table>
    <thead><tr><th>Seq</th><th>Operation</th><th>Work Center</th><th>Est. Hrs</th><th>Start</th><th>Complete</th><th>Sign Off</th><th>QC Pass</th></tr></thead>
    <tbody>
        @foreach($wo->operations->sortBy('sequence') as $op)
        <tr class="sign-row">
            <td>{{ $op->sequence }}</td>
            <td>
                <strong>{{ $op->operation_name }}</strong>
                @if($op->work_instructions)<div style="font-size:10px;color:#555;margin-top:2px">{{ $op->work_instructions }}</div>@endif
            </td>
            <td>{{ $op->work_center ?? '—' }}</td>
            <td>{{ number_format($op->setup_time_est + $op->run_time_est,2) }}</td>
            <td></td><td></td><td></td><td></td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3 style="font-size:12px;font-weight:700;margin-bottom:6px;text-transform:uppercase">Bill of Materials</h3>
<table>
    <thead><tr><th>Part #</th><th>Description</th><th>Qty Required</th><th>Lot / Date Code</th><th>Issued By</th><th>Date</th></tr></thead>
    <tbody>
        @foreach($wo->materials as $mat)
        <tr class="sign-row">
            <td style="font-family:monospace">{{ $mat->part->part_number }}</td>
            <td>{{ $mat->part->description }}</td>
            <td style="font-family:monospace">{{ number_format($mat->qty_required,2) }}</td>
            <td></td><td></td><td></td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    {{ $wo->wo_number }} | {{ $wo->part->part_number }} | {{ $wo->part->description }} | Qty: {{ number_format($wo->quantity,0) }} | Page 1 of 1
</div>

<script>window.onload = () => window.print();</script>
</body>
</html>

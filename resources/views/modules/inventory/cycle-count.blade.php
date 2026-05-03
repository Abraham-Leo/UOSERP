@extends('layouts.app')
@section('title','Cycle Count')
@section('breadcrumb') Inventory / <span class="current">Cycle Count</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-clipboard-check" style="color:var(--accent);margin-right:10px"></i>Cycle Count</h1>
    <p class="page-subtitle">Perform inventory counts and reconciliation</p></div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Count Sheet</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#startCountModal"><i class="fa-solid fa-play"></i> Start New Count</button>
    </div>
</div>
<div class="card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> Cycle Count Worksheet</div></div>
    <form method="POST" action="{{ route('inventory.cycle-count.submit') }}">
    @csrf
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead><tr><th>Part #</th><th>Description</th><th>Warehouse</th><th>Bin</th><th style="text-align:right">System Qty</th><th style="text-align:right;min-width:120px">Counted Qty</th><th style="text-align:right">Variance</th><th>Notes</th></tr></thead>
            <tbody>
                @forelse($items ?? [] as $i => $inv)
                <tr>
                    <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">{{ $inv->part->part_number }}</td>
                    <td style="font-weight:500">{{ $inv->part->description }}</td>
                    <td style="font-size:13px;color:var(--text-muted)">{{ $inv->warehouse->name }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">{{ $inv->binLocation->code ?? 'Default' }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:600">{{ number_format($inv->qty_on_hand,2) }}</td>
                    <td>
                        <input type="hidden" name="items[{{ $i }}][inventory_id]" value="{{ $inv->id }}">
                        <input type="number" name="items[{{ $i }}][counted_qty]" class="form-control form-control-sm text-end count-input" value="" min="0" step="0.001" placeholder="{{ number_format($inv->qty_on_hand,2) }}" style="width:110px;margin-left:auto" data-system="{{ $inv->qty_on_hand }}" oninput="calcVariance(this)">
                    </td>
                    <td style="text-align:right">
                        <span class="variance-display" style="font-family:monospace;font-size:12.5px;font-weight:600;color:var(--text-muted)">—</span>
                    </td>
                    <td><input type="text" name="items[{{ $i }}][notes]" class="form-control form-control-sm" placeholder="Notes..."></td>
                </tr>
                @empty
                @for($i=0;$i<5;$i++)
                <tr>
                    <td style="font-family:monospace;font-size:12.5px;color:var(--accent)">COMP-{{ str_pad($i+1,4,'0',STR_PAD_LEFT) }}</td>
                    <td style="font-weight:500">Sample Part {{ $i+1 }}</td>
                    <td style="font-size:13px;color:var(--text-muted)">Main Warehouse</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted)">A-{{ $i+1 }}-01</td>
                    <td style="text-align:right;font-family:monospace;font-weight:600">{{ rand(50,500) }}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-end count-input" placeholder="Enter count..." style="width:110px;margin-left:auto" data-system="{{ rand(50,500) }}" oninput="calcVariance(this)">
                    </td>
                    <td style="text-align:right"><span class="variance-display" style="font-family:monospace;font-size:12.5px;font-weight:600;color:var(--text-muted)">—</span></td>
                    <td><input type="text" class="form-control form-control-sm" placeholder="Notes..."></td>
                </tr>
                @endfor
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body" style="border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:10px">
        <button type="reset" class="btn btn-secondary">Reset</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Submit Count</button>
    </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
function calcVariance(input) {
    const system = parseFloat(input.dataset.system) || 0;
    const counted = parseFloat(input.value);
    const display = input.closest('tr').querySelector('.variance-display');
    if (isNaN(counted)) { display.textContent = '—'; display.style.color = 'var(--text-muted)'; return; }
    const variance = counted - system;
    display.textContent = (variance >= 0 ? '+' : '') + variance.toFixed(2);
    display.style.color = variance === 0 ? 'var(--success)' : variance > 0 ? 'var(--warning)' : 'var(--danger)';
}
</script>
@endpush

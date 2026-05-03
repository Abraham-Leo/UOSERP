@extends('layouts.app')
@section('title','A/P Aging Report')
@section('breadcrumb') Finance / Reports / <span class="current">A/P Aging</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--purple);margin-right:10px"></i>A/P Aging Report</h1></div>
    <div class="d-flex gap-2">
        <input type="date" class="form-control" style="width:150px" value="{{ now()->format('Y-m-d') }}">
        <button class="btn btn-secondary" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
        <button class="btn btn-primary"><i class="fa-solid fa-file-excel"></i> Export</button>
    </div>
</div>
@php
$vendors = [
    ['DigiKey Corp',8400,0,0,0,8400],
    ['Acme Electronics',24600,12200,0,0,36800],
    ['MetalMart Inc',0,1900,0,0,1900],
    ['Wire Works LLC',12200,0,8700,0,20900],
    ['Contract Fab Co',0,8000,6100,6900,21000],
];
$totals = [45200,22100,14800,6900,89000];
@endphp
<div class="card table-card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-table" style="color:var(--purple)"></i> A/P Aging by Vendor</div></div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Vendor</th>
                    <th style="text-align:right;background:rgba(16,185,129,0.08)">Current (0–30)</th>
                    <th style="text-align:right;background:rgba(245,158,11,0.08)">31–60 Days</th>
                    <th style="text-align:right;background:rgba(239,68,68,0.08)">61–90 Days</th>
                    <th style="text-align:right;background:rgba(239,68,68,0.15)">90+ Days</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $row)
                <tr>
                    <td style="font-weight:500">{{ $row[0] }}</td>
                    @foreach([1,2,3,4] as $i)
                    <td style="text-align:right;font-family:monospace;font-weight:{{ $row[$i] > 0 ? '600' : '400' }};color:{{ $row[$i] > 0 ? ($i >= 3 ? 'var(--danger)' : ($i >= 2 ? 'var(--warning)' : 'var(--success)')) : 'var(--text-muted)' }}">
                        {{ $row[$i] > 0 ? '$'.number_format($row[$i]) : '—' }}
                    </td>
                    @endforeach
                    <td style="text-align:right;font-weight:700;font-family:monospace">${{ number_format($row[5]) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:var(--accent-soft)">
                    <td style="font-weight:700;padding:12px 14px">TOTALS</td>
                    @foreach([0,1,2,3] as $i)
                    <td style="text-align:right;font-weight:700;font-family:monospace;padding:12px 14px">${{ number_format($totals[$i]) }}</td>
                    @endforeach
                    <td style="text-align:right;font-weight:700;font-family:monospace;color:var(--purple);padding:12px 14px">${{ number_format($totals[4]) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

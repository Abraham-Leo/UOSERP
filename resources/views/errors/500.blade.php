@extends('layouts.app')
@section('title','500 — Server Error')
@section('content')
<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 20px;text-align:center">
    <div style="font-size:80px;font-weight:800;color:var(--danger);font-family:'Sora',sans-serif;line-height:1;opacity:0.3">500</div>
    <h2 style="font-size:22px;font-weight:700;margin:16px 0 8px">Something went wrong</h2>
    <p style="color:var(--text-muted);margin-bottom:24px;max-width:400px">An internal server error occurred. Please try again or contact your administrator.</p>
    <div class="d-flex gap-2">
        <button onclick="window.history.back()" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Go Back</button>
        <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="fa-solid fa-house"></i> Dashboard</a>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title','404 — Page Not Found')
@section('content')
<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 20px;text-align:center">
    <div style="font-size:80px;font-weight:800;color:var(--border);font-family:'Sora',sans-serif;line-height:1">404</div>
    <h2 style="font-size:22px;font-weight:700;margin:16px 0 8px">Page Not Found</h2>
    <p style="color:var(--text-muted);margin-bottom:24px;max-width:400px">The page you're looking for doesn't exist or has been moved.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="fa-solid fa-house"></i> Back to Dashboard</a>
</div>
@endsection

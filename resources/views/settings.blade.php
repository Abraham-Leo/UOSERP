@extends('layouts.app')
@section('title','Settings')
@section('breadcrumb') Admin / <span class="current">Settings</span> @endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-gear" style="color:var(--accent);margin-right:10px"></i>System Settings</h1>
    <p class="page-subtitle">Configure application-wide settings</p></div>
</div>
<div class="row g-4">
    <div class="col-lg-3">
        <div class="card" style="padding:0">
            <div class="nav flex-column" style="padding:8px">
                @foreach([
                    ['company','fa-building','Company Info'],
                    ['email','fa-envelope','Email / SMTP'],
                    ['finance','fa-dollar-sign','Finance Defaults'],
                    ['inventory','fa-warehouse','Inventory'],
                    ['theme','fa-palette','Theme & Display'],
                    ['backup','fa-database','Backup & Data'],
                ] as [$id,$icon,$label])
                <a href="#{{ $id }}" class="nav-link" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;color:var(--text-muted);text-decoration:none;font-size:13.5px" onclick="showSection('{{ $id }}',this)">
                    <i class="fa-solid {{ $icon }}" style="width:16px;text-align:center"></i>{{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div id="section-company" class="settings-section">
            <div class="card">
                <div class="card-header"><div class="card-title">Company Information</div></div>
                <div class="card-body">
                    <form method="POST" action="#">@csrf
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" value="{{ config('app.name') }}"></div>
                            <div class="col-md-6"><label class="form-label">App URL</label>
                                <input type="url" name="app_url" class="form-control" value="{{ config('app.url') }}"></div>
                            <div class="col-md-4"><label class="form-label">Phone</label>
                                <input type="text" name="company_phone" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label">Email</label>
                                <input type="email" name="company_email" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label">Timezone</label>
                                <select name="timezone" class="form-select">
                                    <option>UTC</option>
                                    <option>America/New_York</option>
                                    <option>America/Chicago</option>
                                    <option>America/Los_Angeles</option>
                                    <option>Asia/Jakarta</option>
                                </select>
                            </div>
                            <div class="col-12" style="margin-top:8px">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function showSection(id, el) {
    document.querySelectorAll('.settings-section').forEach(s => s.style.display = 'none');
    const sec = document.getElementById('section-' + id);
    if (sec) sec.style.display = 'block';
    document.querySelectorAll('.nav-link').forEach(a => {
        a.style.background = 'transparent';
        a.style.color = 'var(--text-muted)';
    });
    el.style.background = 'var(--accent-soft)';
    el.style.color = 'var(--accent)';
}
document.addEventListener('DOMContentLoaded', () => {
    const first = document.querySelector('.nav-link');
    if (first) showSection('company', first);
});
</script>
@endpush

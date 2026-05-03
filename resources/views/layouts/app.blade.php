<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UOS ERP') }} — @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&family=Sora:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <!-- Flatpickr DatePicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        :root {
            --primary: #0f172a;
            --primary-light: #1e293b;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --accent-soft: rgba(59,130,246,0.12);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --purple: #8b5cf6;
            --orange: #f97316;
            --sidebar-w: 270px;
            --topbar-h: 64px;
            --bg: #f0f4f8;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow: 0 4px 16px rgba(0,0,0,0.07), 0 2px 6px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.1), 0 4px 12px rgba(0,0,0,0.05);
            --radius: 12px;
            --radius-sm: 8px;
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--primary);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: transform var(--transition);
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            text-decoration: none;
        }

        .logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), #818cf8);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 800;
            color: white;
            font-family: 'Sora', sans-serif;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(59,130,246,0.4);
        }

        .logo-text {
            display: flex; flex-direction: column;
        }

        .logo-name {
            font-family: 'Sora', sans-serif;
            font-size: 16px; font-weight: 700;
            color: #fff; letter-spacing: -0.3px;
        }

        .logo-sub {
            font-size: 10px; color: #64748b;
            letter-spacing: 1px; text-transform: uppercase;
            font-weight: 500;
        }

        .sidebar-nav { padding: 12px 0; flex: 1; }

        .nav-section-label {
            font-size: 10px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #475569;
            font-weight: 600;
            padding: 16px 20px 6px;
            font-family: 'DM Mono', monospace;
        }

        .nav-item { position: relative; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 400;
            transition: all var(--transition);
            border-radius: 0;
            cursor: pointer;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: #e2e8f0;
            background: rgba(255,255,255,0.05);
        }

        .nav-link.active {
            color: #fff;
            background: rgba(59,130,246,0.15);
            border-left: 3px solid var(--accent);
        }

        .nav-link .nav-icon {
            width: 20px;
            font-size: 14px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-link .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }

        .nav-link .nav-chevron {
            margin-left: auto;
            font-size: 11px;
            transition: transform var(--transition);
        }

        .nav-link[aria-expanded="true"] .nav-chevron {
            transform: rotate(90deg);
        }

        .nav-submenu {
            background: rgba(0,0,0,0.15);
        }

        .nav-submenu .nav-link {
            padding-left: 48px;
            font-size: 13px;
            color: #64748b;
        }

        .nav-submenu .nav-link:hover { color: #94a3b8; }
        .nav-submenu .nav-link.active { color: #7dd3fc; border-left-color: #7dd3fc; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.04);
            cursor: pointer;
            transition: background var(--transition);
        }

        .sidebar-user:hover { background: rgba(255,255,255,0.08); }

        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--purple));
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }

        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 13px; font-weight: 500; color: #e2e8f0; truncate; }
        .user-role { font-size: 11px; color: #475569; }

        /* ===== TOPBAR ===== */
        .topbar {
            position: fixed;
            left: var(--sidebar-w);
            right: 0;
            top: 0;
            height: var(--topbar-h);
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            z-index: 999;
            box-shadow: var(--shadow-sm);
        }

        .topbar-breadcrumb {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .topbar-breadcrumb .current {
            color: var(--text);
            font-weight: 600;
        }

        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .topbar-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--bg);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            transition: all var(--transition);
            position: relative;
            text-decoration: none;
        }

        .topbar-btn:hover {
            background: var(--accent-soft);
            border-color: var(--accent);
            color: var(--accent);
        }

        .topbar-btn .badge-dot {
            position: absolute; top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid var(--card-bg);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .page-content { padding: 28px 28px; }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-title {
            font-family: 'Sora', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body { padding: 20px; }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }

        .stat-card.accent::before { background: var(--accent); }
        .stat-card.success::before { background: var(--success); }
        .stat-card.warning::before { background: var(--warning); }
        .stat-card.danger::before { background: var(--danger); }
        .stat-card.purple::before { background: var(--purple); }
        .stat-card.info::before { background: var(--info); }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.accent { background: rgba(59,130,246,0.1); color: var(--accent); }
        .stat-icon.success { background: rgba(16,185,129,0.1); color: var(--success); }
        .stat-icon.warning { background: rgba(245,158,11,0.1); color: var(--warning); }
        .stat-icon.danger { background: rgba(239,68,68,0.1); color: var(--danger); }
        .stat-icon.purple { background: rgba(139,92,246,0.1); color: var(--purple); }
        .stat-icon.info { background: rgba(6,182,212,0.1); color: var(--info); }

        .stat-content { flex: 1; }
        .stat-value {
            font-family: 'Sora', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }
        .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .stat-change {
            font-size: 11.5px;
            font-weight: 600;
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .stat-change.up { color: var(--success); }
        .stat-change.down { color: var(--danger); }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 500;
            transition: all var(--transition);
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }
        .btn-primary:hover {
            background: var(--accent-hover);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--accent);
            border-color: var(--accent);
        }
        .btn-outline-primary:hover { background: var(--accent-soft); }

        .btn-success { background: var(--success); color: #fff; border-color: var(--success); }
        .btn-success:hover { background: #059669; color: #fff; }

        .btn-danger { background: var(--danger); color: #fff; border-color: var(--danger); }
        .btn-danger:hover { background: #dc2626; color: #fff; }

        .btn-warning { background: var(--warning); color: #fff; border-color: var(--warning); }
        .btn-warning:hover { background: #d97706; color: #fff; }

        .btn-secondary {
            background: var(--bg);
            color: var(--text);
            border-color: var(--border);
        }
        .btn-secondary:hover { background: #e2e8f0; }

        .btn-sm { padding: 5px 12px; font-size: 12.5px; }
        .btn-lg { padding: 11px 22px; font-size: 15px; }
        .btn-icon { padding: 8px; }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .badge-success { background: rgba(16,185,129,0.1); color: #059669; }
        .badge-warning { background: rgba(245,158,11,0.1); color: #d97706; }
        .badge-danger { background: rgba(239,68,68,0.1); color: #dc2626; }
        .badge-info { background: rgba(6,182,212,0.1); color: #0891b2; }
        .badge-primary { background: rgba(59,130,246,0.1); color: #2563eb; }
        .badge-secondary { background: rgba(100,116,139,0.1); color: #475569; }
        .badge-purple { background: rgba(139,92,246,0.1); color: #7c3aed; }

        /* ===== TABLE ===== */
        .table-card { overflow: hidden; }

        .erp-table {
            width: 100%;
            border-collapse: collapse;
        }

        .erp-table thead th {
            background: #f8fafc;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 11px 14px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .erp-table tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13.5px;
            vertical-align: middle;
        }

        .erp-table tbody tr:last-child td { border-bottom: none; }

        .erp-table tbody tr:hover td { background: #f8fafc; }

        .erp-table tbody tr { transition: background var(--transition); }

        /* ===== FORMS ===== */
        .form-label {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.3px;
            margin-bottom: 5px;
        }

        .form-control, .form-select {
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            color: var(--text);
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            transition: all var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
            outline: none;
        }

        .form-control::placeholder { color: var(--text-light); }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid;
        }

        .alert-success { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.25); color: #065f46; }
        .alert-danger { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.25); color: #991b1b; }
        .alert-warning { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.25); color: #92400e; }
        .alert-info { background: rgba(6,182,212,0.08); border-color: rgba(6,182,212,0.25); color: #155e75; }

        /* ===== TABS ===== */
        .nav-tabs {
            border-bottom: 2px solid var(--border);
            gap: 4px;
        }

        .nav-tabs .nav-link {
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-muted);
            padding: 10px 18px;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            border-radius: 0;
            transition: all var(--transition);
        }

        .nav-tabs .nav-link:hover { color: var(--text); background: var(--bg); }

        .nav-tabs .nav-link.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
            background: transparent;
            font-weight: 600;
        }

        /* ===== MISC ===== */
        .divider { height: 1px; background: var(--border); margin: 16px 0; }

        .text-truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .hover-card { transition: all var(--transition); cursor: pointer; }
        .hover-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }

        code, .mono { font-family: 'DM Mono', monospace; font-size: 12.5px; }

        /* ===== TOAST ===== */
        .toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9999; }

        .erp-toast {
            background: var(--primary);
            color: #fff;
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            min-width: 280px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(16px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* ===== LOADING ===== */
        .loading-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.7);
            backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 48px; height: 48px;
            border: 3px solid rgba(255,255,255,0.1);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .topbar, .main-content { left: 0; margin-left: 0; }
        }

        /* ===== PRINT ===== */
        @media print {
            .sidebar, .topbar { display: none; }
            .main-content { margin-left: 0; padding-top: 0; }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <div class="logo-icon">E</div>
        <div class="logo-text">
            <span class="logo-name">{{ config('app.name', 'UOS ERP') }}</span>
            <span class="logo-sub">Enterprise System</span>
        </div>
    </a>

    <div class="sidebar-nav">
        <!-- Overview -->
        <div class="nav-section-label">Overview</div>
        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-grid-2"></i></span>
                Dashboard
            </a>
        </div>

        <!-- CRM -->
        <div class="nav-section-label">CRM</div>
        <div class="nav-item">
            <a href="{{ route('crm.customers.index') }}" class="nav-link {{ request()->is('crm/customers*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
                Customers
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('crm.leads.index') }}" class="nav-link {{ request()->is('crm/leads*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-user-plus"></i></span>
                Leads
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('crm.opportunities.index') }}" class="nav-link {{ request()->is('crm/opportunities*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-bullseye-arrow"></i></span>
                Opportunities
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('crm.pipeline') }}" class="nav-link {{ request()->routeIs('crm.pipeline') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-filter-circle-dollar"></i></span>
                Sales Pipeline
            </a>
        </div>

        <!-- Sales -->
        <div class="nav-section-label">Sales</div>
        <div class="nav-item">
            <a href="{{ route('sales.quotes.index') }}" class="nav-link {{ request()->is('sales/quotes*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                Quotes
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('sales.orders.index') }}" class="nav-link {{ request()->is('sales/orders*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-cart-flatbed"></i></span>
                Sales Orders
                @if(isset($pendingOrders) && $pendingOrders > 0)
                    <span class="nav-badge">{{ $pendingOrders }}</span>
                @endif
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('sales.invoices.index') }}" class="nav-link {{ request()->is('sales/invoices*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-receipt"></i></span>
                Invoices
            </a>
        </div>

        <!-- Purchasing -->
        <div class="nav-section-label">Purchasing</div>
        <div class="nav-item">
            <a href="{{ route('purchasing.vendors.index') }}" class="nav-link {{ request()->is('purchasing/vendors*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-store"></i></span>
                Vendors
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('purchasing.purchase-orders.index') }}" class="nav-link {{ request()->is('purchasing/purchase-orders*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-file-purchase"></i></span>
                Purchase Orders
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('purchasing.receiving.index') }}" class="nav-link {{ request()->is('purchasing/receiving*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-boxes-packing"></i></span>
                Receiving
            </a>
        </div>

        <!-- Inventory -->
        <div class="nav-section-label">Inventory</div>
        <div class="nav-item">
            <a href="{{ route('inventory.parts.index') }}" class="nav-link {{ request()->is('inventory/parts*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-microchip"></i></span>
                Parts / Items
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('inventory.boms.index') }}" class="nav-link {{ request()->is('inventory/boms*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-sitemap"></i></span>
                BOMs
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('inventory.stock.index') }}" class="nav-link {{ request()->is('inventory/stock*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-warehouse"></i></span>
                Stock / Warehouse
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('inventory.cycle-count') }}" class="nav-link {{ request()->routeIs('inventory.cycle-count') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                Cycle Count
            </a>
        </div>

        <!-- Production -->
        <div class="nav-section-label">Production (MES)</div>
        <div class="nav-item">
            <a href="{{ route('production.work-orders.index') }}" class="nav-link {{ request()->is('production/work-orders*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-gear"></i></span>
                Work Orders
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('production.scheduling.index') }}" class="nav-link {{ request()->routeIs('production.scheduling.index') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-calendar-days"></i></span>
                Scheduling
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('production.mrp.index') }}" class="nav-link {{ request()->routeIs('production.mrp.index') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chart-network"></i></span>
                MRP
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('production.shop-floor') }}" class="nav-link {{ request()->routeIs('production.shop-floor') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-industry-windows"></i></span>
                Shop Floor
            </a>
        </div>

        <!-- Shipping -->
        <div class="nav-section-label">Shipping</div>
        <div class="nav-item">
            <a href="{{ route('shipping.shipments.index') }}" class="nav-link {{ request()->is('shipping/shipments*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-truck-fast"></i></span>
                Shipments
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('shipping.rma.index') }}" class="nav-link {{ request()->is('shipping/rma*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-rotate-left"></i></span>
                RMAs
            </a>
        </div>

        <!-- Finance -->
        <div class="nav-section-label">Finance</div>
        <div class="nav-item">
            <a href="{{ route('finance.dashboard') }}" class="nav-link {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span>
                Finance Overview
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('finance.accounts-payable.index') }}" class="nav-link {{ request()->is('finance/accounts-payable*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-money-bill-transfer"></i></span>
                Accounts Payable
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('finance.accounts-receivable.index') }}" class="nav-link {{ request()->is('finance/accounts-receivable*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                Accounts Receivable
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('finance.gl') }}" class="nav-link {{ request()->routeIs('finance.gl') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-book-open"></i></span>
                General Ledger
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#financeReports" role="button" aria-expanded="false">
                <span class="nav-icon"><i class="fa-solid fa-chart-bar"></i></span>
                Reports
                <span class="nav-chevron"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <div class="collapse nav-submenu" id="financeReports">
                <a href="{{ route('finance.reports.pl') }}" class="nav-link">Profit & Loss</a>
                <a href="{{ route('finance.reports.bs') }}" class="nav-link">Balance Sheet</a>
                <a href="{{ route('finance.reports.cf') }}" class="nav-link">Cash Flow</a>
                <a href="{{ route('finance.reports.ar-aging') }}" class="nav-link">A/R Aging</a>
                <a href="{{ route('finance.reports.ap-aging') }}" class="nav-link">A/P Aging</a>
            </div>
        </div>

        <!-- QMS -->
        <div class="nav-section-label">Quality (QMS)</div>
        <div class="nav-item">
            <a href="{{ route('qms.ncr.index') }}" class="nav-link {{ request()->is('qms/ncr*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                NCR
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('qms.eco.index') }}" class="nav-link {{ request()->is('qms/eco*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-code-branch"></i></span>
                ECO / ECR
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('qms.inspections.index') }}" class="nav-link {{ request()->is('qms/inspections*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-magnifying-glass-chart"></i></span>
                Inspections
            </a>
        </div>

        <!-- Admin -->
        @if(auth()->user()?->hasRole('admin'))
        <div class="nav-section-label">Administration</div>
        <div class="nav-item">
            <a href="{{ route('hr.users.index') }}" class="nav-link {{ request()->is('hr/users*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-user-gear"></i></span>
                User Management
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('hr.roles.index') }}" class="nav-link {{ request()->is('hr/roles*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-shield-halved"></i></span>
                Roles & Permissions
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('tools.assets.index') }}" class="nav-link {{ request()->is('tools/assets*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                Tools & Equipment
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('documents.index') }}" class="nav-link {{ request()->is('documents*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-folder-tree"></i></span>
                Document Control
            </a>
        </div>
        @endif
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-user" data-bs-toggle="dropdown">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()?->name ?? 'User' }}</div>
                <div class="user-role">{{ auth()->user()?->roles->first()?->name ?? 'Staff' }}</div>
            </div>
            <i class="fa-solid fa-ellipsis" style="color:#475569;font-size:12px;"></i>
        </div>
    </div>
</nav>

<!-- Topbar -->
<header class="topbar">
    <button class="topbar-btn d-lg-none" id="sidebarToggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="topbar-breadcrumb">
        <i class="fa-solid fa-house" style="font-size:12px;"></i>
        <span>/</span>
        @yield('breadcrumb')
    </div>

    <div class="topbar-actions ms-auto">
        <!-- Search -->
        <div class="position-relative">
            <input type="text" id="globalSearch" class="form-control" placeholder="Search..." style="width:220px;padding-left:34px;font-size:13px;">
            <i class="fa-solid fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-light);font-size:12px;"></i>
        </div>

        <!-- Notifications -->
        <div class="dropdown">
            <button class="topbar-btn" data-bs-toggle="dropdown" id="notifBtn" title="Notifications">
                <i class="fa-solid fa-bell"></i>
                <span class="badge-dot" id="notifDot"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end" style="width:340px;padding:0;font-size:13px;" id="notifDropdown">
                <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;font-size:14px">Notifications</span>
                    <button class="btn btn-secondary btn-sm" onclick="markAllRead()" style="font-size:11px;padding:3px 8px">Mark all read</button>
                </div>
                <div id="notifList" style="max-height:320px;overflow-y:auto">
                    @php
                    $notifications = [
                        ['danger','fa-exclamation-triangle','12 MRP shortages detected','Click to view MRP →','production.mrp.index','2m ago'],
                        ['warning','fa-clock','PO VCHR-2025-0084 overdue 30 days','Vendor: Mouser Electronics','finance.accounts-payable.index','15m ago'],
                        ['info','fa-boxes-packing','Receipt received: REC-2025-0142','3 items received from DigiKey','purchasing.receiving.index','1h ago'],
                        ['success','fa-check-circle','Sales Order SO-2025-0847 shipped','Customer: Acme Industries','shipping.shipments.index','2h ago'],
                        ['warning','fa-triangle-exclamation','NCR-2025-0042 requires disposition','Capacitor 100uF lot rejected','qms.ncr.index','3h ago'],
                        ['info','fa-file-invoice','Invoice INV-2025-0294 sent','To: Acme Industries — $48,200','sales.invoices.index','5h ago'],
                    ];
                    @endphp
                    @foreach($notifications as $notif)
                    <a href="{{ route($notif[4]) }}" class="notif-item" style="display:flex;align-items:flex-start;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border);text-decoration:none;color:var(--text);transition:background 0.15s" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='transparent'">
                        <div style="width:34px;height:34px;border-radius:8px;background:rgba(var(--{{ $notif[0] }}-rgb,0,0,0),0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px">
                            <i class="fa-solid {{ $notif[1] }}" style="font-size:13px;color:var(--{{ $notif[0] }})"></i>
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:600;font-size:12.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $notif[2] }}</div>
                            <div style="font-size:11.5px;color:var(--text-muted);margin-top:1px">{{ $notif[3] }}</div>
                            <div style="font-size:11px;color:var(--text-light);margin-top:3px">{{ $notif[5] }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
                <div style="padding:10px 16px;border-top:1px solid var(--border);text-align:center">
                    <a href="{{ route('dashboard') }}" style="font-size:12.5px;color:var(--accent);text-decoration:none;font-weight:500">View all notifications →</a>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <a href="{{ route('settings') }}" class="topbar-btn" title="Settings">
            <i class="fa-solid fa-gear"></i>
        </a>

        <!-- User Menu -->
        <div class="dropdown">
            <div class="topbar-btn" data-bs-toggle="dropdown" style="width:auto;padding:5px 10px;gap:8px;cursor:pointer;">
                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>
                <div style="line-height:1.2;">
                    <div style="font-size:13px;color:var(--text);font-weight:500;">{{ auth()->user()?->name ?? 'User' }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">{{ auth()->user()?->roles?->first()?->name ?? 'User' }}</div>
                </div>
                <i class="fa-solid fa-chevron-down" style="font-size:10px;color:var(--text-muted);"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end" style="min-width:200px;font-size:13px;padding:6px 0">
                <li>
                    <div style="padding:10px 16px;border-bottom:1px solid var(--border);margin-bottom:4px">
                        <div style="font-weight:600">{{ auth()->user()?->name ?? 'User' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted)">{{ auth()->user()?->email ?? '' }}</div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fa-solid fa-user me-2" style="color:var(--accent);width:16px"></i>My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('settings') }}">
                        <i class="fa-solid fa-gear me-2" style="color:var(--text-muted);width:16px"></i>Settings
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="toggleTheme()">
                        <i class="fa-solid fa-circle-half-stroke me-2" style="color:var(--text-muted);width:16px"></i>Toggle Theme
                    </a>
                </li>
                <li><hr class="dropdown-divider" style="margin:4px 0"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="color:var(--danger)">
                            <i class="fa-solid fa-right-from-bracket me-2" style="width:16px"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">
    <div class="page-content">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success mb-3">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-3">
                <i class="fa-solid fa-circle-xmark"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="fa-solid fa-circle-xmark"></i>
                <div><strong>Please fix the errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
// CSRF Token setup
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

// Sidebar toggle
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
});

// Init DataTables
$(document).ready(function() {
    $('.erp-datatable').DataTable({
        responsive: true,
        pageLength: 25,
        dom: '<"d-flex align-items-center justify-content-between mb-3"lf>t<"d-flex align-items-center justify-content-between mt-3"ip>',
        language: { search: '', searchPlaceholder: 'Search...', lengthMenu: 'Show _MENU_' }
    });

    // Select2
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });

    // Flatpickr
    $('.datepicker').flatpickr({ dateFormat: 'Y-m-d', allowInput: true });
});

// Toast function
function showToast(message, type = 'success') {
    const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation', info: 'fa-circle-info' };
    const colors = { success: '#10b981', error: '#ef4444', warning: '#f59e0b', info: '#06b6d4' };
    const toast = document.createElement('div');
    toast.className = 'erp-toast';
    toast.innerHTML = `<i class="fa-solid ${icons[type]}" style="color:${colors[type]}"></i><span>${message}</span>`;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => a.style.opacity = '0');
    setTimeout(() => document.querySelectorAll('.alert').forEach(a => a.remove()), 500);
}, 5000);

// Notification functions
function markAllRead() {
    const dot = document.getElementById('notifDot');
    if (dot) dot.style.display = 'none';
    document.querySelectorAll('.notif-item').forEach(el => el.style.opacity = '0.5');
    showToast('All notifications marked as read', 'success');
}

// Theme toggle
function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme') || 'light';
    const next = current === 'light' ? 'dark' : 'light';
    html.setAttribute('data-theme', next);
    localStorage.setItem('erp-theme', next);
    showToast('Switched to ' + next + ' mode', 'info');
}
(function() {
    const saved = localStorage.getItem('erp-theme');
    if (saved) document.documentElement.setAttribute('data-theme', saved);
})();
</script>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px">
            <div class="modal-header" style="border-bottom:1px solid var(--border)">
                <h5 class="modal-title" style="font-size:15px;font-weight:600">
                    <i class="fa-solid fa-user" style="color:var(--accent);margin-right:8px"></i>My Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div style="text-align:center;margin-bottom:20px">
                        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;margin:0 auto 10px">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div style="font-weight:700;font-size:15px">{{ auth()->user()?->name }}</div>
                        <div style="font-size:12.5px;color:var(--text-muted)">{{ auth()->user()?->email }}</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()?->name }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()?->email }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Leave blank to keep current">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border)">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - TAPEM Sport</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 30px 20px;
            background: #e67e22;
            border-bottom: none;
            text-align: center;
        }

        .sidebar-header h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            font-size: 15px;
        }

        .menu-item:hover {
            background: #34495e;
            color: white;
            border-left-color: #e67e22;
        }

        .menu-item.active {
            background: #e67e22;
            color: white;
            border-left-color: #d35400;
            font-weight: 600;
        }

        .menu-item svg {
            width: 22px;
            height: 22px;
            margin-right: 15px;
        }

        .menu-item i {
            font-size: 18px;
            margin-right: 15px;
            width: 22px;
            text-align: center;
        }

        .menu-section {
            padding: 20px 25px 8px;
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.5;
            font-weight: 700;
            letter-spacing: 1.5px;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #ecf0f1;
        }

        /* Top Bar */
        .topbar {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left h1 {
            font-size: 28px;
            color: #2c3e50;
            font-weight: 700;
        }

        .topbar-date {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #e67e22;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .btn-logout {
            padding: 10px 20px;
            background: #e67e22;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: #d35400;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(230, 126, 34, 0.3);
        }

        /* Content Area */
        .content-area {
            padding: 40px;
            flex: 1;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card-content h3 {
            font-size: 13px;
            color: white;
            margin-bottom: 12px;
            font-weight: 600;
            opacity: 0.95;
        }

        .stat-card-content .value {
            font-size: 36px;
            font-weight: 700;
            color: white;
            line-height: 1;
        }

        .stat-card-icon {
            font-size: 50px;
            opacity: 0.3;
            color: white;
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .stat-card.orange-red {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        }

        .stat-card.green {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .stat-card.red {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        /* Card */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: 2px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafafa;
        }

        .card-header h2 {
            font-size: 18px;
            color: #2c3e50;
            font-weight: 600;
        }

        .card-body {
            padding: 0;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        table tr:hover {
            background: #f8f9fa;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* Badge */
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="AFE Sport" style="height: 40px; width: auto; margin-bottom: 10px;">
            <h2>AFE Sport</h2>
            <p>Admin Panel</p>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i>🏠</i>
                Dashboard
            </a>

            <a href="{{ route('admin.lapangan.index') }}" class="menu-item {{ request()->routeIs('admin.lapangan.*') ? 'active' : '' }}">
                <i>🏷️</i>
                Lapangan
            </a>

            <a href="{{ route('admin.booking.index') }}" class="menu-item {{ request()->routeIs('admin.booking.index') ? 'active' : '' }}">
                <i>☕</i>
                Booking
            </a>

            <a href="{{ route('admin.kasir.index') }}" class="menu-item {{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
                <i>💰</i>
                Kasir (Walk-in)
            </a>

            <a href="{{ route('admin.booking.verifikasi') }}" class="menu-item {{ request()->routeIs('admin.booking.verifikasi') ? 'active' : '' }}">
                <i>📝</i>
                Verifikasi
            </a>

            <a href="{{ route('admin.transaksi') }}" class="menu-item {{ request()->routeIs('admin.transaksi') ? 'active' : '' }}">
                <i>💳</i>
                Transaksi
            </a>

            <a href="{{ route('admin.analitik') }}" class="menu-item {{ request()->routeIs('admin.analitik') ? 'active' : '' }}">
                <i>📊</i>
                Analitik
            </a>
        </nav>

        <div style="position: absolute; bottom: 0; width: 100%; padding: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                    <i>🚪</i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="topbar-left">
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="topbar-right">
                <div class="topbar-date">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    ✗ {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin: 10px 0 0 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @yield('scripts')
</body>
</html>

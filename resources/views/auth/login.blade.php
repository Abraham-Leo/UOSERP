<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ config('app.name', 'UOS ERP') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Sora:wght@400;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            overflow: hidden;
        }

        /* Left Panel */
        .login-left {
            width: 55%;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
            top: -100px; left: -100px;
            animation: pulse 8s ease-in-out infinite;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%);
            bottom: -50px; right: -50px;
            animation: pulse 10s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 1; }
        }

        /* Floating grid dots */
        .grid-bg {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 1px 1px, rgba(255,255,255,0.04) 1px, transparent 0);
            background-size: 36px 36px;
        }

        .left-content {
            position: relative;
            z-index: 1;
            max-width: 480px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 60px;
        }

        .brand-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #3b82f6, #818cf8);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif;
            font-size: 22px; font-weight: 800;
            color: #fff;
            box-shadow: 0 8px 24px rgba(59,130,246,0.4);
        }

        .brand-name {
            font-family: 'Sora', sans-serif;
            font-size: 22px; font-weight: 700;
            color: #fff;
        }

        .brand-tagline { font-size: 12px; color: #475569; letter-spacing: 1px; text-transform: uppercase; }

        .hero-heading {
            font-family: 'Sora', sans-serif;
            font-size: 42px;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -1.5px;
        }

        .hero-heading span {
            background: linear-gradient(135deg, #3b82f6, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 15px;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 48px;
        }

        /* Feature items */
        .features-list { display: flex; flex-direction: column; gap: 16px; }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(59,130,246,0.08);
            border-color: rgba(59,130,246,0.2);
            transform: translateX(4px);
        }

        .feature-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .feature-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .feature-icon.green { background: rgba(16,185,129,0.15); color: #34d399; }
        .feature-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
        .feature-icon.orange { background: rgba(249,115,22,0.15); color: #fb923c; }

        .feature-text { font-size: 14px; color: #94a3b8; }
        .feature-text strong { color: #e2e8f0; display: block; font-size: 13.5px; }

        /* Right Panel */
        .login-right {
            width: 45%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 52px;
            position: relative;
        }

        .login-form-wrap {
            width: 100%;
            max-width: 380px;
        }

        .login-form-heading {
            font-family: 'Sora', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.8px;
            margin-bottom: 6px;
        }

        .login-form-sub {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 36px;
        }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: #0f172a;
            background: #f8fafc;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .form-input::placeholder { color: #cbd5e1; }

        .form-extras {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-check {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #64748b; cursor: pointer;
        }

        .remember-check input[type="checkbox"] { accent-color: #3b82f6; }

        .forgot-link {
            font-size: 13px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(59,130,246,0.35);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59,130,246,0.45);
        }

        .btn-login:active { transform: translateY(0); }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0;
            color: #cbd5e1;
            font-size: 12px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .demo-credentials {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 16px;
        }

        .demo-cred-title {
            font-size: 11.5px;
            font-weight: 600;
            color: #0369a1;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .demo-cred-item {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            color: #0369a1;
            padding: 3px 0;
        }

        .demo-cred-item strong { font-family: 'DM Mono', monospace; }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .version-tag {
            position: absolute;
            bottom: 24px;
            right: 24px;
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <!-- Left: Branding -->
    <div class="login-left">
        <div class="grid-bg"></div>
        <div class="left-content">
            <div class="brand-logo">
                <div class="brand-icon">E</div>
                <div>
                    <div class="brand-name">{{ config('app.name', 'UOS ERP') }}</div>
                    <div class="brand-tagline">Enterprise Resource Planning</div>
                </div>
            </div>

            <div class="hero-heading">
                Run Your Business<br><span>Efficiently</span>
            </div>

            <p class="hero-desc">
                Complete manufacturing ERP — from quote to invoice, inventory to production, quality to financials. All in one powerful platform.
            </p>

            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-icon blue"><i class="fa-solid fa-chart-network"></i></div>
                    <div class="feature-text">
                        <strong>MRP & Production</strong>
                        Material requirements, scheduling & shop floor
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon green"><i class="fa-solid fa-dollar-sign"></i></div>
                    <div class="feature-text">
                        <strong>Full Financial Suite</strong>
                        A/P, A/R, GL, reports & bank reconciliation
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon purple"><i class="fa-solid fa-shield-check"></i></div>
                    <div class="feature-text">
                        <strong>Quality Management</strong>
                        NCR, ECO/ECR, inspections & CAPA
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon orange"><i class="fa-solid fa-users"></i></div>
                    <div class="feature-text">
                        <strong>CRM & Sales</strong>
                        Leads, quotes, orders & customer portal
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="login-right">
        <div class="login-form-wrap">
            <h1 class="login-form-heading">Welcome back</h1>
            <p class="login-form-sub">Sign in to your ERP account</p>

            @if($errors->any())
                <div class="error-msg">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-input"
                               placeholder="you@company.com"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" name="password" id="passwordInput"
                               class="form-input" placeholder="Enter your password" required>
                        <i class="fa-solid fa-eye" id="togglePwd"
                           style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#94a3b8;cursor:pointer;font-size:14px;"></i>
                    </div>
                </div>

                <div class="form-extras">
                    <label class="remember-check">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Sign In to ERP
                </button>
            </form>

            <div class="divider">Demo Credentials</div>

            <div class="demo-credentials">
                <div class="demo-cred-title">Test Accounts</div>
                <div class="demo-cred-item"><span>Admin:</span> <strong>admin@erp.com / password</strong></div>
                <div class="demo-cred-item"><span>Sales:</span> <strong>sales@erp.com / password</strong></div>
                <div class="demo-cred-item"><span>Production:</span> <strong>prod@erp.com / password</strong></div>
                <div class="demo-cred-item"><span>Finance:</span> <strong>finance@erp.com / password</strong></div>
            </div>
        </div>

        <div class="version-tag">v1.0.0</div>
    </div>

    <script>
        document.getElementById('togglePwd')?.addEventListener('click', function() {
            const pwd = document.getElementById('passwordInput');
            const isText = pwd.type === 'text';
            pwd.type = isText ? 'password' : 'text';
            this.className = `fa-solid fa-eye${isText ? '' : '-slash'}`;
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login — ERP Produksi PT. Actmetal Indonesia">
    <title>Login — ERP Produksi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1E3A5F 0%, #1E6FD9 50%, #0EA5E9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* Brand / Logo */
        .brand {
            text-align: center;
            margin-bottom: 32px;
            color: #fff;
        }

        .brand-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,.15);
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 14px;
            backdrop-filter: blur(8px);
        }

        .brand h1 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand p {
            font-size: 13px;
            opacity: .75;
            margin-top: 4px;
        }

        /* Card */
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1A202C;
            margin-bottom: 4px;
        }

        .card-sub {
            font-size: 13px;
            color: #A0AEC0;
            margin-bottom: 28px;
        }

        /* Alert error */
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #DC2626;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1A202C;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #A0AEC0;
            font-size: 17px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 44px;
            padding: 0 12px 0 40px;
            border: 1.5px solid #E5E9F0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #1A202C;
            background: #fff;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-input:focus {
            border-color: #1E6FD9;
            box-shadow: 0 0 0 3px rgba(30,111,217,.12);
        }

        .form-input.is-error { border-color: #EF4444; }

        .toggle-pass {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #A0AEC0;
            font-size: 17px;
            padding: 0;
            display: flex;
        }

        .field-error {
            font-size: 12px;
            color: #EF4444;
            margin-top: 4px;
        }

        /* Remember + Forgot */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #4A5568;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #1E6FD9;
            cursor: pointer;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, #1E6FD9, #0EA5E9);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(30,111,217,.35);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(30,111,217,.45);
        }

        .btn-login:active { transform: translateY(0); }

        /* Divider info akun */
        .account-info {
            margin-top: 24px;
            padding: 14px;
            background: #F8FAFC;
            border: 1px solid #E5E9F0;
            border-radius: 10px;
            font-size: 12px;
            color: #718096;
        }

        .account-info p { margin-bottom: 4px; font-weight: 600; color: #4A5568; }

        .account-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }

        .role-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .role-admin    { background: #EBF3FF; color: #1E6FD9; }
        .role-operator { background: #ECFDF5; color: #10B981; }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- Brand --}}
    <div class="brand">
        <div class="brand-icon">🏭</div>
        <h1>ERP PRODUKSI</h1>
        <p>PT. ACTMETAL INDONESIA</p>
    </div>

    {{-- Card --}}
    <div class="login-card">
        <div class="card-title">Selamat Datang!</div>
        <div class="card-sub">Masuk untuk mengakses sistem produksi</div>

        {{-- Error --}}
        @if($errors->any())
            <div class="alert-error">
                <i class="ph ph-warning-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" id="form-login">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrapper">
                    <i class="ph ph-envelope input-icon"></i>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        autocomplete="email"
                        autofocus
                    >
                </div>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <i class="ph ph-lock input-icon"></i>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-pass" id="btn-toggle-pass" onclick="togglePassword()">
                        <i class="ph ph-eye" id="pass-icon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember me --}}
            <div class="form-footer">
                <label class="remember">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-login" id="btn-submit-login">
                <i class="ph ph-sign-in"></i>
                Masuk
            </button>
        </form>

        {{-- Info akun (development helper) --}}
        @if(config('app.debug'))
        <div class="account-info">
            <p>Akun tersedia:</p>
            <div class="account-row">
                <span>admin@erp.com / <strong>admin123</strong></span>
                <span class="role-badge role-admin">Admin</span>
            </div>
            <div class="account-row">
                <span>operator@erp.com / <strong>operator123</strong></span>
                <span class="role-badge role-operator">Operator</span>
            </div>
        </div>
        @endif
    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('pass-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ph ph-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'ph ph-eye';
    }
}
</script>
</body>
</html>

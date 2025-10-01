<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PLN Asset Monitoring System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --pln-blue: #0066cc;        /* Biru Primer */
            --pln-blue-dark: #004c99;    /* Biru Darker untuk hover */
            --pln-yellow: #ffcc00;      /* Kuning PLN */
            --card-radius: 1rem;        /* Radius kartu yang lebih modern */
        }
        
        /* 🎨 Background dan Layout */
        .vh-100 { min-height: 100vh; }
        .login-bg { 
            background: #f0f4f8; /* Latar belakang abu-abu terang yang bersih */
        }

        /* 🖼️ Card Login yang Rapi dan Modern */
        .login-card {
            border-radius: var(--card-radius); 
            overflow: hidden;
            max-width: 900px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); /* Shadow yang lebih halus dan dalam */
        }

        /* 💡 Styling Tombol PLN Blue */
        .btn-pln {
            background-color: var(--pln-blue);
            border-color: var(--pln-blue);
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 102, 204, 0.3); 
            font-size: 1rem;
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
        }
        .btn-pln:hover {
            background-color: var(--pln-blue-dark);
            border-color: var(--pln-blue-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 102, 204, 0.4);
            color: #fff;
        }

        /* 📝 Form Controls dan Input Group */
        .form-control, .input-group-text {
            border-radius: 0.5rem; /* Konsisten dengan tombol */
            padding: 0.65rem 1rem;
        }
        .input-group-text {
            background-color: #e9ecef; /* Latar belakang untuk icon */
            border-right: none;
            border-color: #dee2e6;
            color: var(--pln-blue); /* Warna ikon PLN Blue */
        }
        .form-control:focus {
            border-color: var(--pln-blue);
            box-shadow: 0 0 0 0.15rem rgba(0, 102, 204, 0.25);
        }

        /* 👁️ Toggle Password Button */
        .input-group-password .btn {
            border-radius: 0 0.5rem 0.5rem 0; /* Radius hanya di kanan */
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
            padding: 0.65rem 0.75rem;
            z-index: 5;
            transition: color 0.2s ease;
        }
        .input-group-password .btn:hover {
            color: var(--pln-blue);
            background-color: #dee2e6;
        }

        /* 🖼️ Ilustrasi Panel (Desktop Split) */
        .illustration-col {
            background: var(--pln-blue); 
            /* Menggunakan gradien halus untuk latar belakang yang lebih menarik */
            /* background: linear-gradient(135deg, var(--pln-blue), #003366); */ 
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem !important;
        }
        .illustration-col img {
            max-width: 85%;
            height: auto;
            border-radius: 0.75rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            object-fit: contain;
        }

        /* 🔄 CAPTCHA Styling */
        .captcha-area {
            display: flex;
            align-items: center;
            gap: 1rem; /* Jarak antara gambar dan tombol refresh */
        }
        .captcha-image {
            background-color: #fff;
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
            padding: 0.1rem;
            height: 44px; 
            width: 140px; 
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .captcha-image img {
            max-width: 100%;
            height: 100%;
            object-fit: contain;
            border: none;
        }
        .refresh-btn {
            color: var(--pln-blue); 
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }
        .refresh-btn:hover {
            transform: rotate(360deg);
        }
        
        /* 📱 Responsif */
        @media (max-width: 991.98px) {
            .login-card {
                max-width: 450px;
                margin: auto;
            }
            .p-lg-5 {
                padding: 2.5rem !important;
            }
            /* Sembunyikan ilustrasi di mobile */
            .illustration-col {
                display: none !important;
            }
        }
    </style>
</head>
<body class="login-bg">

    <section class="vh-100 d-flex align-items-center justify-content-center">
        <div class="container py-4">
            <div class="card login-card border-0">
                <div class="row g-0">
                    
                    <div class="col-lg-5 d-none d-lg-flex illustration-col">
                        <div class="text-center">
                            <h2 class="text-white fw-bold mb-3">Sistem Pemantauan Aset</h2>
                            <img src="https://cdn-icons-png.flaticon.com/512/9746/9746437.png"
                                 class="img-fluid" alt="Asset Monitoring Illustration">
                            <p class="small mt-3 text-light opacity-75">Kelola aset Anda dengan efisien dan terpercaya.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-7 p-4 p-lg-5 bg-white"> 
                        <div class="text-center mb-4">
                            <i class="fas fa-bolt fa-3x mb-3" style="color: var(--pln-yellow);"></i> 
                            <h1 class="h4 fw-bolder text-dark mb-1">PLN Asset Monitoring</h1>
                            <p class="text-muted small">Masukkan kredensial Anda untuk mengakses sistem</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-start p-3 mb-4 rounded-3" role="alert">
                                <i class="fas fa-exclamation-circle flex-shrink-0 me-3 mt-1"></i>
                                <div>
                                    <strong class="fw-semibold">Login Gagal!</strong>
                                    <span class="d-block mt-0.5 small">{{ $errors->first() }}</span>
                                </div>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="contoh@pln.co.id">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group input-group-password">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" id="password" required autocomplete="current-password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password Anda">
                                    <button type="button" id="togglePassword" class="btn btn-outline-secondary" tabindex="-1" title="Lihat Password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="captcha" class="form-label fw-semibold">Verifikasi CAPTCHA</label>
                                <div class="captcha-area mb-2">
                                    <div class="captcha-image">{!! captcha_img('flat') !!}</div>
                                    <button type="button" id="refresh-captcha" class="btn btn-link refresh-btn p-0" title="Refresh CAPTCHA">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <input type="text" name="captcha" id="captcha" required
                                    class="form-control @error('captcha') is-invalid @enderror" placeholder="Masukkan kode di atas">
                                @error('captcha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                    <label class="form-check-label text-muted small" for="remember">Ingat saya</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold" style="color: var(--pln-blue);">Lupa Password?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-pln w-100 fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // Ambil elemen-elemen
        const refreshCaptchaBtn = document.getElementById('refresh-captcha');
        const captchaImageContainer = document.querySelector('.captcha-image');
        const captchaInput = document.getElementById('captcha');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        // Fungsi Refresh CAPTCHA
        if (refreshCaptchaBtn) {
            refreshCaptchaBtn.addEventListener('click', function () {
                const icon = this.querySelector('i');
                icon.classList.add('fa-spin'); 
                icon.style.pointerEvents = 'none'; // Cegah klik berulang saat loading

                // Sesuaikan route jika Anda menggunakan Laravel
                const refreshRoute = '{{ route('captcha.login.refresh') }}'; 

                fetch(refreshRoute, {
                    method: 'GET',
                    cache: 'no-cache',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.captcha) {
                        captchaImageContainer.innerHTML = data.captcha;
                        captchaInput.value = '';
                        captchaInput.focus();
                    } else {
                        console.error('Data CAPTCHA tidak ditemukan');
                    }
                })
                .catch(error => {
                    console.error('Error fetching CAPTCHA:', error);
                    alert('Gagal memperbarui CAPTCHA. Silakan coba lagi.');
                })
                .finally(() => {
                    icon.classList.remove('fa-spin');
                    icon.style.pointerEvents = 'auto'; 
                });
            });
        }

        // Auto refresh CAPTCHA jika ada error validasi
        @if($errors->has('captcha'))
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    if (refreshCaptchaBtn) {
                        refreshCaptchaBtn.click();
                    }
                }, 100);
            });
        @endif

        // Toggle lihat/hide password
        if (togglePasswordBtn) {
            togglePasswordBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                const icon = this.querySelector('i');
                icon.classList.remove(isPassword ? 'fa-eye' : 'fa-eye-slash');
                icon.classList.add(isPassword ? 'fa-eye-slash' : 'fa-eye');
                this.setAttribute('title', isPassword ? 'Sembunyikan Password' : 'Lihat Password');
            });
        }
        
        // Validasi Form Klien Bootstrap
        (function () {
          'use strict'
          const forms = document.querySelectorAll('.needs-validation')
          Array.from(forms)
            .forEach(function (form) {
              form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                  event.preventDefault()
                  event.stopPropagation()
                }
                form.classList.add('was-validated')
              }, false)
            })
        })()
    </script>

</body>
</html>
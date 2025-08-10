<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN Asset Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0066cc;
            --secondary: #ff6600;
            --dark: #1a1a2e;
            --light: #f5f5f5;
        }

        .hero-gradient {
            background: linear-gradient(135deg, var(--primary), #004d99);
        }

        .feature-card {
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-bottom-color: var(--secondary);
        }

        .btn-primary {
            background-color: var(--primary);
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #0055aa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: var(--secondary);
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background-color: #e65c00;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-sans bg-gray-50">

    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('assets/images/foto.png') }}" alt="Logo PLN" class="h-10 w-auto">
                <span class="text-xl font-bold text-blue-800">Asset Monitoring</span>
            </div>
        </div>
    </nav>

    <section class="hero-gradient text-white py-20">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center">
            
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                    Sistem Monitoring Aset Terintegrasi PLN
                </h1>
                <p class="text-xl mb-8">
                    Kelola, pantau, dan rawat seluruh aset perusahaan dengan solusi terpadu berbasis digital.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}" class="btn-secondary text-white px-8 py-3 rounded-full font-medium">
                        Mulai Sekarang
                    </a>
                </div>
            </div>

            <div class="md:w-1/2">
                <img src="{{ asset('assets/images/bg.png') }}" alt="Dashboard Monitoring Aset PLN" class="rounded-lg shadow-xl w-full">
            </div>
            
        </div>
    </section>

</body>
</html>

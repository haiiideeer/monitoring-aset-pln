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
            background-color: #004d99;
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

    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto max-w-7xl px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('asset/images/foto.png') }}" alt="Logo PLN" class="h-10 w-auto">
                <span class="text-xl font-bold text-blue-800">Asset Monitoring</span>
            </div>
            
            <!-- Tombol Login -->
            <div>
                <a href="{{ route('login') }}" class="btn-primary text-white px-6 py-2 rounded-full font-medium">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section dengan Slideshow -->
    <section class="hero-gradient text-white py-20">
        <div class="container mx-auto max-w-7xl px-6 flex flex-col md:flex-row items-center">
            
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                    Sistem Monitoring Aset Ekstrakomptabel PLN
                </h1>
                <p class="text-xl mb-8">
                    PT PLN (Persero) UIW Maluku dan Maluku Utara.
                </p>
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
                <!-- Gambar Slideshow -->
                <img id="slideshowImage" src="{{ asset('asset/images/pln_1.jpg') }}" alt="Gambar slideshow PLN" class="rounded-lg shadow-xl w-full">
            </div>
            
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-700 text-white py-12">
        <div class="container mx-auto max-w-7xl px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Profil Perusahaan -->
            <div>
                <h3 class="text-xl font-bold mb-4">PLN Asset Monitoring</h3>
                <p class="text-gray-400">
                    Sistem ini didesain untuk mempermudah pengelolaan dan pemantauan seluruh aset PLN agar operasional lebih efisien dan terorganisir.
                </p>
            </div>
            <!-- Kontak -->
            <div>
                <h3 class="text-xl font-bold mb-4">Kontak Kami</h3>
                <ul class="text-gray-400 space-y-2">
                    <li>
                        <i class="fas fa-map-marker-alt mr-2"></i> Kantor Pusat: Jl. Diponegoro No.2, Kel Ahusen, Kec. Sirimau, Kota Ambon, Maluku 97127
                    </li>
                    <!-- <li>
                        <i class="fas fa-envelope mr-2"></i> Email: info@pln.co.id
                    </li>
                    <li>
                        <i class="fas fa-phone mr-2"></i> Telepon: (021) 1234567
                    </li> -->
                </ul>
            </div>
            <!-- Ikuti Kami -->
            <div>
                <h3 class="text-xl font-bold mb-4">Ikuti Kami</h3>
                <div class="flex space-x-4 text-2xl">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="container mx-auto max-w-7xl px-6 mt-8 text-center text-gray-500 border-t border-gray-700 pt-6">
            <p>&copy; 2025 PLN Asset Monitoring System. PT PLN (Persero) UIW Maluku dan Maluku Utara.</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const images = [
                '{{ asset("asset/images/pln_1.jpg") }}',
                '{{ asset("asset/images/pln_2.jpg") }}',
                '{{ asset("asset/images/pln_3.jpg") }}'
            ];
            const slideshowImage = document.getElementById('slideshowImage');
            let currentIndex = 0;

            function nextImage() {
                currentIndex = (currentIndex + 1) % images.length;
                slideshowImage.src = images[currentIndex];
            }

            // Ganti gambar setiap 4 detik (4000 milidetik)
            setInterval(nextImage, 4000);
        });
    </script>

</body>
</html>

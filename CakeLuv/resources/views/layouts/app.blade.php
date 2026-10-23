<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CakeLuv - Patisserie & Bakery Mewah') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Tailwind CSS (via CDN sementara untuk prototype) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#C5837C',
                        primary_hover: '#b06f68',
                        secondary: '#FAF9F6',
                        dark: '#1A1A1A',
                        gold: '#D4AF37'
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #FAF9F6; }
        ::-webkit-scrollbar-thumb { background: #C5837C; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b06f68; }
        html { scroll-behavior: smooth; }
        #loader {
            position: fixed; inset: 0; z-index: 9999; background: #FAF9F6;
            display: flex; flex-direction: column; justify-content: center; align-items: center; transition: opacity 0.5s ease;
        }
        .spinner {
            width: 50px; height: 50px; border: 3px solid rgba(197, 131, 124, 0.2);
            border-radius: 50%; border-top-color: #C5837C; animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-secondary text-dark font-sans flex flex-col min-h-screen">
    
    <!-- Loading Screen -->
    <div id="loader">
        <div class="spinner mb-4"></div>
        <p class="font-serif text-primary text-xl italic tracking-wider">CakeLuv...</p>
    </div>

    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="w-1/4 flex justify-start">
                    <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center">
                        <span class="font-serif font-bold text-3xl text-primary tracking-tight">CakeLuv.</span>
                    </a>
                </div>
                
                <!-- Nav Links -->
                <div class="hidden md:flex flex-1 justify-center gap-8 items-center">
                    @php 
                        $activeClass = "text-primary font-bold relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-primary";
                        $inactiveClass = "text-gray-600 hover:text-primary";
                        $currentRoute = request()->path();
                    @endphp
                    
                    <a href="{{ url('/') }}" class="text-sm uppercase tracking-wider transition {{ $currentRoute == '/' || $currentRoute == '' ? $activeClass : $inactiveClass }}">Beranda</a>
                    <a href="{{ url('/#about') }}" class="text-sm uppercase tracking-wider transition {{ $inactiveClass }}">Tentang</a>
                    <a href="{{ url('/shop') }}" class="text-sm uppercase tracking-wider transition {{ str_starts_with($currentRoute, 'shop') ? $activeClass : $inactiveClass }}">Katalog</a>
                    <a href="{{ url('/#contact') }}" class="text-sm uppercase tracking-wider transition {{ $inactiveClass }}">Kontak</a>
                </div>

                <!-- Right / Auth & Cart -->
                <div class="w-1/4 flex items-center justify-end space-x-6">
                    <a href="{{ url('/cart') }}" class="text-dark hover:text-primary transition relative group" id="cartIcon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </a>
                    
                    <div class="h-6 w-px bg-gray-300 hidden md:block"></div>
                    
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="hidden md:block text-xs uppercase font-bold tracking-wider text-gold hover:text-yellow-600 transition">Admin</a>
                        @endif
                        <a href="{{ url('/profile') }}" class="text-xs uppercase font-bold tracking-wider text-gray-600 hover:text-primary transition flex items-center gap-2">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" class="w-6 h-6 rounded-full object-cover shadow-sm">
                            @else
                                Profil
                            @endif
                        </a>
                    @else
                        <a href="{{ url('/login') }}" class="text-xs uppercase font-bold tracking-wider text-gray-600 hover:text-primary transition">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-20 pb-10 border-t-[6px] border-primary mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div data-aos="fade-up">
                    <h2 class="font-serif text-3xl font-bold text-primary mb-4">CakeLuv.</h2>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6 font-light">
                        Menghadirkan kebahagiaan di setiap gigitan. Dibuat dengan bahan premium, cinta, dan dedikasi tinggi untuk merayakan momen spesial Anda.
                    </p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="font-serif text-xl font-bold mb-4 tracking-wide">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ url('/shop') }}" class="hover:text-primary transition">Katalog Produk</a></li>
                        <li><a href="{{ url('/#about') }}" class="hover:text-primary transition">Kisah Kami</a></li>
                        <li><a href="{{ url('/cart') }}" class="hover:text-primary transition">Keranjang Belanja</a></li>
                    </ul>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="font-serif text-xl font-bold mb-4 tracking-wide">Kunjungi Kami</h3>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-primary mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span>Jl. Sudirman No. 123, Jakarta Selatan, 12190</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 mt-8 text-center flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} CakeLuv Patisserie. All rights reserved.</p>
                <p class="mt-2 md:mt-0">100% Quality Premium Ingredients | Handcrafted with Love</p>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-[100] flex flex-col gap-3 pointer-events-none"></div>

    <!-- Closed Store Modal -->
    <div id="closedModal" class="fixed inset-0 z-[200] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm transition-opacity" onclick="closeStoreModal()"></div>
        
        <!-- Modal Content -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border-t-8 border-primary animate-bounce-short">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-red-100 sm:mx-0 sm:h-12 sm:w-12 shadow-inner">
                            <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-6 sm:text-left">
                            <h3 class="text-2xl leading-6 font-serif font-bold text-dark mb-2" id="modal-title">
                                Maaf, Toko Sedang Tutup
                            </h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Saat ini kami sedang beristirahat. Anda tidak dapat melakukan pemesanan (Add to Cart atau Checkout) di luar jam operasional.
                                </p>
                                <div class="mt-4 bg-secondary/50 p-4 rounded-xl border border-gray-100">
                                    <p class="text-sm font-bold text-dark"><span class="text-primary mr-2">🕒</span> Jam Operasional Kami:</p>
                                    <p class="text-lg font-serif font-bold text-primary mt-1">08:00 - 20:00 WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="button" onclick="closeStoreModal()" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-8 py-3 bg-primary text-base font-bold text-white hover:bg-primary_hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        .animate-bounce-short { animation: bounce-short 0.5s ease-out; }
        @keyframes bounce-short {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgClass = type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-500' : 'bg-dark');
            const icon = type === 'success' 
                ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>' 
                : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>';

            toast.className = `flex items-center gap-3 px-5 py-4 rounded-xl shadow-2xl text-white text-sm font-bold tracking-wide transform transition-all duration-300 translate-y-10 opacity-0 pointer-events-auto ${bgClass}`;
            toast.innerHTML = `<span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/20">${icon}</span> <span>${message}</span>`;
            
            container.appendChild(toast);
            
            requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function openStoreModal() {
            document.getElementById('closedModal').classList.remove('hidden');
        }

        function closeStoreModal() {
            document.getElementById('closedModal').classList.add('hidden');
        }

        @if(session('success'))
            window.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));
        @endif
        
        @if(session('error'))
            window.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));
        @endif

        @if(session('closed'))
            window.addEventListener('DOMContentLoaded', () => openStoreModal());
        @endif

        AOS.init({ duration: 800, easing: 'ease-in-out', once: true, offset: 50 });

        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 500);
        });
    </script>
</body>
</html>

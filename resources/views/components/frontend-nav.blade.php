<div class="bg-white border-b border-gray-200 shadow-md fixed w-full top-0 z-50">
    <div class="container mx-auto max-w-7xl px-4 py-4 flex justify-between items-center">
        
        {{-- LOGO BRANDING --}}
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Mubaakor" class="h-6 w-auto object-contain">
            <span class="text-xl font-extrabold text-lime-500">NEWS MUBAAKOR</span>
        </a>
        
        {{-- NAVIGASI KATEGORI --}}
        <nav class="hidden md:flex space-x-6">
            @foreach ($categories as $cat)
                <a href="{{ route('home', $cat->slug) }}" class="text-gray-700 hover:text-gray-200 font-medium transition">{{ $cat->name }}</a>
            @endforeach
        </nav>
        
        {{-- TOMBOL PENCARIAN & LOGIN --}}
        <div class="flex items-center space-x-4">
            <button id="search-toggle" class="text-gray-700 hover:text-gray-200 transition focus:outline-none" aria-label="Toggle Search">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </div>
    </div>
    
    <div id="search-bar-container" class="absolute top-full left-0 w-full bg-white shadow-xl p-3 transform -translate-y-full opacity-0 transition-all duration-300 pointer-events-none">
        <div class="container mx-auto max-w-7xl px-4">
            <form action="{{ route('search') }}" method="GET" class="flex">
                <input type="search" name="q" placeholder="Cari berita..." 
                       class="w-full py-2 px-4 rounded-l-lg text-gray-800 focus:ring-0 focus:border-transparent border-none" required>
                <button type="submit" class="bg-traparent text-lime-500 hover:text-gray-200 px-4 rounded-r-lg transition">Cari</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('search-toggle');
        const searchContainer = document.getElementById('search-bar-container');

        toggleButton.addEventListener('click', function() {
            // Toggle opacity dan posisi untuk efek sliding
            if (searchContainer.classList.contains('opacity-0')) {
                searchContainer.classList.remove('opacity-0', '-translate-y-full', 'pointer-events-none');
                searchContainer.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
                searchContainer.querySelector('input').focus(); // Fokus ke input setelah muncul
            } else {
                searchContainer.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                searchContainer.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
        });
    });
</script>
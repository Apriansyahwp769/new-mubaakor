<footer class="bg-gray-900 text-gray-200 mt-12 pt-10 pb-6 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-b border-gray-700 pb-8 mb-6">
            
            {{-- Kolom 1: Branding --}}
            <div class="md:col-span-1">
                <h3 class="text-2xl font-extrabold text-white mb-3">NEWS MUBAAKOR</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Menyajikan berita terkini dan terpercaya dengan integritas dan kecepatan.
                </p>
                <div class="mt-4">
                    <h4 class="text-lg font-bold text-white mb-2 border-l-4 border-lime-500 pl-3">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-500 transition text-2xl">
                            <i class="fab fa-facebook-square"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-pink-500 transition text-2xl">
                            <i class="fab fa-instagram-square"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-red-500 transition text-2xl">
                            <i class="fab fa-youtube-square"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kolom 2: Kategori --}}
            <div class="md:col-span-1">
                <h3 class="text-xl font-bold text-white mb-5 border-l-4 border-lime-500 pl-3">Kategori Populer</h3>
                <ul class="space-y-3 text-sm">
                    @if (isset($categories))
                        @foreach ($categories->take(6) as $cat)
                            <li>
                                <a href="{{ route('home', ['category' => $cat->slug]) }}" 
                                   class="text-gray-300 hover:text-lime-500 transition flex items-center">
                                    <i class="fas fa-tag mr-2 text-lime-500 text-xs"></i>{{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            {{-- Kolom 3: Kontak --}}
            <div class="md:col-span-1">
                <h3 class="text-xl font-bold text-white mb-5 border-l-4 border-lime-500 pl-3">Akses Cepat</h3>
                <ul class="space-y-3 text-sm">
                    <li class="text-gray-400 flex items-center">
                        <i class="fas fa-envelope mr-2 text-lime-500"></i>Email: contact@mubaakor.com
                    </li>
                </ul>
            </div>
        </div>
        
        {{-- Copyright --}}
        <div class="text-center text-gray-500 text-sm pt-4">
            &copy; {{ date('Y') }} NEWS MUBAAKOR. All rights reserved.
        </div>
    </div>
</footer>
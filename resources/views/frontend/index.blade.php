<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Utama - Website Berita Sederhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Styling untuk card dan item */
        .list-item {
            transition: background-color 0.2s ease-in-out;
        }
        .list-item:hover {
            background-color: #f7f7f7;
        }
        .img-container {
            flex-shrink: 0;
        }
        .post-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background-size: cover;
            background-position: center;
        }
        .post-card:hover {
            transform: translateY(-3px); 
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.15); /* Shadow yang lebih dramatis */
        }
        .text-shadow-strong {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.7);
        }
        
        /* 🔥 CSS KUSTOM UNTUK CAROUSEL: ARROW ON HOVER DAN SCROLLBAR 🔥 */
        .carousel-container .carousel-arrow {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }
        .carousel-container:hover .carousel-arrow {
            opacity: 1;
            pointer-events: auto;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
            scroll-behavior: smooth;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* 🔥 CSS BARU: IMAGE ZOOM (Ken Burns Effect) 🔥 */
        .image-zoom-container {
            overflow: hidden; /* Menyembunyikan zoom berlebihan */
        }
        .image-zoom-container img {
            transition: transform 0.5s ease-in-out; /* Transisi yang mulus */
        }
        .post-card:hover .image-zoom-container img {
            transform: scale(1.05); /* Zoom 5% saat hover */
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased pt-20">
    
    <x-frontend-nav :categories="$categories" />

    <div class="container mx-auto max-w-7xl px-4 py-8 pt-0">
        
        {{-- LOGIKA PEMBAGIAN POST UNTUK HERO DAN SIDEBAR --}}
        @php
            $allPosts = $posts->getCollection()->values(); 
            $heroPost = $allPosts->shift(); 
            $sidePosts = $allPosts->splice(0, 2); 
        @endphp

        <div class="mb-10">
            <h2 class="text-3xl font-extrabold text-gray-800 border-l-4 border-indigo-600 pl-4 mb-6">
                @if (isset($search_query))
                    Hasil Pencarian untuk: <span class="text-indigo-600">"{{ $search_query }}"</span>
                @elseif (isset($category) && $category->exists)
                    Berita Terbaru — Kategori: <span class="text-indigo-600">{{ $category->name }}</span>
                @else
                    Berita Terbaru
                @endif
            </h2>
        </div>


        {{-- KONTEN UTAMA: PENCARIAN ATAU HOMEPAGE --}}
        @if (isset($search_query))
        
            {{-- JALUR A: TAMPILAN HASIL PENCARIAN (Horizontal Carousel) --}}
            <div class="bg-white rounded-xl shadow-lg p-6 relative carousel-container">
                
                <button class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg transition duration-300 ml-2 hidden md:block carousel-arrow" 
                        onclick="scrollCarousel('search', -300)" type="button">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                {{-- CONTAINER SCROLL: Diberi ID unik 'carousel-search' --}}
                <div id="carousel-search" class="flex space-x-6 overflow-x-auto pb-4 hide-scrollbar"> 
                    @forelse ($posts as $post) 
                        
                        <a href="{{ route('posts.show', $post->slug) }}" 
                           class="post-card block bg-white rounded-lg overflow-hidden shadow-md transition hover:shadow-lg duration-200 flex-shrink-0"
                           style="width: 280px;">
                            
                            {{-- Gambar Thumbnail (dengan zoom container) --}}
                            <div class="w-full h-40 overflow-hidden image-zoom-container">
                                @if ($post->image_path)
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-xs text-gray-400">NO IMAGE</div>
                                @endif
                            </div>
                            
                            <div class="p-4">
                                <span class="text-xs font-semibold text-pink-600 uppercase">{{ $post->category->name }}</span>
                                <h3 class="text-lg font-bold text-gray-900 mt-1 line-clamp-2 hover:text-indigo-700">
                                    {{ $post->title }}
                                </h3>
                                <div class="text-sm text-gray-500 mt-2 flex items-center space-x-1">
                                    <span>{{ $post->published_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="p-6 text-center text-xl text-gray-600">Tidak ada hasil yang cocok dengan kata kunci "{{ $search_query }}".</p>
                    @endforelse
                </div>

                <button class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg transition duration-300 mr-2 hidden md:block carousel-arrow" 
                        onclick="scrollCarousel('search', 300)" type="button">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
                
            </div>

        @else
        
            {{-- JALUR B: TAMPILAN HOMEPAGE NORMAL (Hero/Sidebar/Carousel) --}}
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                
                {{-- KOLOM UTAMA (2/3 LEBAR) --}}
                <div class="lg:col-span-2">
                    @if ($heroPost)
                    <a href="{{ route('posts.show', $heroPost->slug) }}" class="post-card relative block h-96 rounded-xl overflow-hidden shadow-xl"
                    style="background-image: url('{{ asset('storage/' . $heroPost->image_path) }}');">
                        <div class="absolute inset-0 bg-black bg-opacity-40 hover:bg-opacity-50 transition duration-300"></div>
                        <div class="absolute bottom-0 p-8 text-white w-full">
                            <span class="text-xs font-bold bg-pink-600 px-3 py-1 rounded uppercase">{{ $heroPost->category->name }}</span>
                            <h2 class="text-4xl font-black mt-3 leading-snug text-shadow-strong">
                                {{ $heroPost->title }}
                            </h2>
                            <span class="text-shadow-strong mt-2 block">{{ Str::limit(strip_tags($heroPost->content), 80) }}</span>
                        </div>
                    </a>
                    @endif
                </div>

                {{-- SIDEBAR MINI (1/3 LEBAR) --}}
                <div class="lg:col-span-1 space-y-4">
                    @forelse ($sidePosts as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="post-card relative block h-44 rounded-xl overflow-hidden shadow-md">
                            <div class="absolute inset-0 bg-black bg-opacity-30 hover:bg-opacity-40 transition duration-300"
                                style="background-image: url('{{ asset('storage/' . $post->image_path) }}'); background-size: cover; background-position: center;"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 p-4 text-white w-full">
                                <span class="text-xs font-bold text-pink-500">{{ $post->category->name }}</span>
                                <h3 class="text-lg font-bold mt-1 text-shadow-strong line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                            </div>
                        </a>
                    @empty
                        <p class="text-center text-gray-600 p-4 bg-white rounded-lg shadow-md">Tidak ada post sampingan.</p>
                    @endforelse
                </div>
            </div>
            
            @foreach ($categories as $cat)
                @php
                    $categoryPosts = App\Models\Post::where('category_id', $cat->id)
                                                    ->where('status', 'published')
                                                    ->latest()
                                                    ->get();
                @endphp

                @if ($categoryPosts->count() > 0)
                    <div class="mt-12 relative"> 
                        <h2 class="text-2xl font-extrabold text-gray-800 border-l-4 border-indigo-600 pl-4 mb-6">
                            {{ $cat->name }}
                        </h2>
                        
                        <div class="bg-white rounded-xl shadow-lg p-6 relative carousel-container"> 
                            
                            <button class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg transition duration-300 ml-2 hidden md:block carousel-arrow" 
                                    onclick="scrollCarousel('{{ $cat->slug }}', -300)" type="button">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>

                            <div id="carousel-{{ $cat->slug }}" class="flex space-x-6 overflow-x-auto pb-4 hide-scrollbar"> 
                                @foreach ($categoryPosts as $post)
                                
                                <a href="{{ route('posts.show', $post->slug) }}" 
                                   class="post-card block bg-white rounded-lg overflow-hidden shadow-md transition hover:shadow-lg duration-200 flex-shrink-0"
                                   style="width: 280px;">
                                    
                                    {{-- Gambar Thumbnail (dengan zoom container) --}}
                                    <div class="w-full h-40 overflow-hidden image-zoom-container">
                                        @if ($post->image_path)
                                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-xs text-gray-400">NO IMAGE</div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-4">
                                        <span class="text-xs font-semibold text-pink-600 uppercase">{{ $post->category->name }}</span>
                                        <h3 class="text-lg font-bold text-gray-900 mt-1 line-clamp-2 hover:text-indigo-700">
                                            {{ $post->title }}
                                        </h3>
                                        <div class="text-sm text-gray-500 mt-2 flex items-center space-x-1">
                                            <span>{{ $post->published_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>

                            <button class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow-lg transition duration-300 mr-2 hidden md:block carousel-arrow" 
                                    onclick="scrollCarousel('{{ $cat->slug }}', 300)" type="button">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>

                        </div>
                    </div>
                @endif
            @endforeach
        
        @endif
        {{-- AKHIR KONTEN UTAMA --}}


        <div class="mt-12 flex justify-center">
            {{ $posts->links() }}
        </div>
    </div>
    <x-frontend-footer :categories="$categories" />
    
    <script>
        function scrollCarousel(slug, amount) {
            const carousel = document.getElementById('carousel-' + slug);
            if (carousel) {
                // Menggunakan scrollBy untuk menggeser sejauh 'amount' (300px)
                carousel.scrollBy({
                    left: amount,
                    behavior: 'smooth' // Membuat geseran lebih mulus
                });
            }
        }
    </script>
</body>
</html>
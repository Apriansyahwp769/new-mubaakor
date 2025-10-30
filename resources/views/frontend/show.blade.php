<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <style>
        /* Mengatur styling untuk konten Quill agar gambar tidak melebihi lebar */
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 1em;
            margin-bottom: 1em;
        }
        /* Menambahkan font weight dan line height yang nyaman untuk membaca */
        .prose {
            font-size: 1.125rem; /* 18px */
            line-height: 1.8;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased pt-20"> {{-- Tambahkan pt-20 untuk Navbar fixed --}}
    
    {{-- 🔥 1. INTEGRASI NAVBAR 🔥 --}}
    <x-frontend-nav :categories="$categories" />

    <div class="container mx-auto p-4 max-w-5xl">
    
    <div class="bg-white p-6 sm:p-10 rounded-xl shadow-lg relative"> {{-- 🔥 Tambahkan relative di sini 🔥 --}}
        
        <a href="{{ route('home') }}" 
           class="absolute top-0 right-0 m-4 sm:m-6 p-2 bg-gray-100 hover:bg-gray-200 rounded-full text-indigo-600 transition shadow-md focus:outline-none"
           title="Kembali ke Beranda">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="pt-6"> {{-- Tambahkan padding di sini untuk memberi ruang pada tombol kembali --}}

            <span class="text-sm font-bold text-pink-600 uppercase tracking-widest">{{ $post->category->name }}</span>
            
            <h1 class="text-4xl sm:text-5xl font-black text-gray-900 mt-3 leading-tight">
                {{ $post->title }}
            </h1>
            
            <div class="mt-5 border-y border-gray-200 py-3 mb-6 flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center space-x-3">
                    <span class="font-bold text-gray-800">{{ $post->user->name }}</span>
                    <span class="text-gray-400">|</span>
                    <span><i class="far fa-clock mr-1"></i> {{ $post->published_at->format('d F Y H:i') }} WIB</span>
                </div>
                <span class="text-gray-500">{{ $post->published_at->diffForHumans() }}</span>
            </div>

            @if ($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-96 object-cover my-6 rounded-xl shadow-md">
            @endif

            <div class="entry-content mt-8 text-gray-800 prose prose-xl max-w-none">
                {!! $post->content !!} 
            </div>

            <!-- <div class="mt-10 border-t pt-6 flex items-center">
                <button id="like-button" 
                        data-post-id="{{ $post->id }}" 
                        class="py-2 px-6 rounded-full font-bold transition duration-300 flex items-center space-x-2
                        {{ $hasLiked ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <span>{{ $hasLiked ? '❤ Sudah Disukai' : '♡ Suka' }}</span>
                </button>
                <span id="like-count" class="ml-4 text-xl font-extrabold text-gray-800">
                    {{ $totalLikes }} Suka
                </span>
            </div> -->

        </div>
    </div>
    {{-- 🔥 3. INTEGRASI FOOTER 🔥 --}}
         </div>

    <x-frontend-footer :categories="$categories" />


   <script>

        document.getElementById('like-button').addEventListener('click', function() {

            const button = this;

            const postId = button.getAttribute('data-post-id');

            const likeCountSpan = document.getElementById('like-count');

            

            fetch(`/berita/${postId}/like`, {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')

                },

                body: JSON.stringify({})

            })

            .then(response => response.json())

            .then(data => {

                if (data.success) {

                    // Update tampilan tombol

                    if (data.action === 'liked') {

                        button.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');

                        button.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');

                        button.innerHTML = '<span>❤ Sudah Disukai</span>';

                    } else {

                        button.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700');

                        button.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');

                        button.innerHTML = '<span>♡ Suka</span>';

                    }

                    // Update jumlah like

                    likeCountSpan.textContent = `${data.total_likes} Suka`;

                }

            })

            .catch(error => {

                console.error('Error:', error);

                alert('Terjadi kesalahan saat memproses suka.');

            });

        });

    </script>
</body>
</html>
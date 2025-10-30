<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pusat Aktivitas Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-indigo-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase">Total Berita</p>
                                <h3 class="mt-1 text-3xl font-extrabold text-gray-900">{{ $totalPosts }}</h3>
                            </div>
                            </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-green-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase">Berita Terbit</p>
                                <h3 class="mt-1 text-3xl font-extrabold text-gray-900">{{ $publishedPosts }}</h3>
                            </div>
                            </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-pink-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase">Total Kategori</p>
                                <h3 class="mt-1 text-3xl font-extrabold text-gray-900">{{ $totalCategories }}</h3>
                            </div>
                            </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- KOLOM KIRI (Aktivitas Terbaru) --}}
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-extrabold text-gray-800 mb-4 border-b pb-2">5 Berita Terbaru</h3>
                        
                        <div class="divide-y divide-gray-100">
                            @forelse ($latestPosts as $post)
                            <div class="py-3 flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-semibold text-pink-600">{{ $post->category->name }}</span>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-gray-800 hover:text-indigo-600 font-medium line-clamp-1">
                                        {{ $post->title }}
                                    </a>
                                </div>
                                <span class="text-xs text-gray-400 flex-shrink-0">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            @empty
                                <p class="text-gray-500">Belum ada berita yang diinput.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- KOLOM KANAN (Akses Cepat) --}}
                    <div class="lg:col-span-1 bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-extrabold text-gray-800 mb-4 border-b pb-2">Akses Cepat</h3>
                            <a href="{{ route('admin.posts.create') }}" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 mb-3">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tulis Berita Baru
                            </a>
                            <a href="{{ route('admin.posts.index') }}" class="w-full inline-flex justify-center items-center px-4 py-3 border border-indigo-500 text-sm font-medium rounded-lg shadow-sm text-indigo-600 bg-white hover:bg-indigo-50 transition duration-150">
                                Kelola Semua Berita
                            </a>
                        </div>
                        
                        <p class="text-xs text-gray-400 mt-6 text-center">Sistem Dashboard Mubaakor</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
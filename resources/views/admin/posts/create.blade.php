<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tulis Berita Baru') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Form harus memiliki enctype="multipart/form-data" untuk file upload --}}
                    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" id="post-form">
                        @csrf

                        {{-- 1. Judul Berita --}}
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Berita')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- 2. Kategori dan Status --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="category_id" :value="__('Kategori')" />
                                <select id="category_id" name="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Terbitkan)</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        {{-- 3. Gambar Utama --}}
                        <div class="mb-4">
                            <x-input-label for="image" :value="__('Gambar Utama (Maks 2MB)')" />
                            <input id="image" class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm p-1" type="file" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />

                            <img id="image-preview" src="" alt="Pratinjau Gambar" class="w-48 h-auto object-cover rounded-md mt-3 hidden">
                        </div>

                        {{-- 4. Konten Berita --}}
                        <div class="mb-4">
                            <x-input-label for="content" :value="__('Isi Konten Berita')" />

                            {{-- WADAH EDITOR QUILL --}}
                            <div id="editor-container" style="height: 300px;" class="bg-white border border-gray-300 rounded-md"></div>

                            {{-- <textarea> ASLI: Diisi oleh JS dan disembunyikan --}}
                            <textarea id="content" name="content" class="hidden" required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.posts.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                Batal
                            </a>
                            {{-- Ubah type menjadi button dan tambahkan ID agar submit dikontrol oleh JS --}}
                            <x-primary-button class="ml-4" type="button" id="submit-button">
                                {{ __('Simpan Berita') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // 1. Definisikan Font
        var Font = Quill.import('formats/font');
        Font.whitelist = ['sans-serif', 'serif', 'monospace', 'arial', 'times-new-roman'];
        Quill.register(Font, true);

        // 2. Inisialisasi Quill
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tulis konten berita di sini...',
            modules: {
                toolbar: {
                    container: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'font': [] }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        [{ 'size': ['small', false, 'large', 'huge'] }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['link', 'image', 'video'],
                        ['clean'],
                    ],
                    // Costome Handler LINK (SweetAlert2)
                    handlers: {
                        link: function(value) {
                            if (value) {
                                var range = quill.getSelection();
                                var anchorText = range.length > 0 ? quill.getText(range.index, range.length) : '';

                                Swal.fire({
                                    title: 'Sisipkan Tautan',
                                    html: '<input id="swal-input-url" class="swal2-input" placeholder="Masukkan URL (cth: https://google.com)">' +
                                        '<input id="swal-input-text" class="swal2-input" placeholder="Teks yang Tampil (Anchor Text)" value="' +
                                        anchorText +
                                        '" ' +
                                        (anchorText ? 'style="display:none;"' : '') +
                                        '>',
                                    focusConfirm: false,
                                    showCancelButton: true,
                                    confirmButtonText: 'Sisipkan',
                                    cancelButtonText: 'Batal',
                                    preConfirm: () => {
                                        const url = Swal.getPopup().querySelector('#swal-input-url').value;
                                        const textInput = Swal.getPopup().querySelector('#swal-input-text');
                                        const text = textInput && textInput.style.display !== 'none' ? textInput.value : anchorText;

                                        if (!url) {
                                            Swal.showValidationMessage(`URL wajib diisi`);
                                            return false;
                                        }
                                        return {
                                            url: url,
                                            text: text
                                        };
                                    },
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        const {
                                            url,
                                            text
                                        } = result.value;

                                        if (range.length > 0) {
                                            quill.format('link', url);
                                        } else if (text) {
                                            quill.insertText(range.index, text, 'link', url);
                                        }
                                    }
                                });
                            } else {
                                quill.format('link', false);
                            }
                        },
                    },
                },
            },
        });
        
        // 3. 🔥 PERBAIKAN BUG SELEKSI TEKS 🔥
        quill.on('text-change', function(delta, oldDelta, source) {
            if (source === 'user') {
                var range = quill.getSelection();

                setTimeout(function() {
                    if (range) {
                        quill.setSelection(range.index, range.length, 'silent');
                    }
                }, 0);
            }
        });


        // 4. Isi konten lama jika ada
        quill.root.innerHTML = "{!! old('content') !!}";


        // 5. PERBAIKAN SUBMIT FINAL (Terikat pada klik tombol)
        document.getElementById('submit-button').addEventListener('click', function(event) {
            // Salin konten HTML dari Quill ke textarea yang tersembunyi
            var html = quill.root.innerHTML;
            document.getElementById('content').value = html;

            // Ambil form dan submit secara manual
            const form = document.getElementById('post-form');
            form.submit();
        });


        // 6. Image Preview
        document.getElementById('image').addEventListener('change', function(event) {
            const [file] = event.target.files;
            const preview = document.getElementById('image-preview');

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
                preview.src = '';
            }
        });
    </script>
</x-app-layout>
<?php

namespace App\Listeners;

class CopyToPublicStorage
{
    public function handle($event)
    {
        // Ambil path file yang di-upload
        $filePath = $event->filePath ?? null;
        if (!$filePath) return;

        $src = storage_path('app/public/' . $filePath);
        $dst = public_path('storage/' . $filePath);

        // Buat folder tujuan jika belum ada
        if (!file_exists(dirname($dst))) {
            mkdir(dirname($dst), 0777, true);
        }

        // Copy file
        copy($src, $dst);
    }
}


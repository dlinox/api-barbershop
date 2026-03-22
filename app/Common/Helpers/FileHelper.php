<?php

namespace App\Common\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function generateUniqueFilename(string $name, string $extension): string
    {
        $timestamp = now()->format('YmdHis');
        return "{$name}-{$timestamp}.{$extension}";
    }

    public static function getFileUrl(string $disk, string $filename): string
    {
        return Storage::disk($disk)->url($filename);
    }

    public static function saveFile(string $content, string $filename, string $disk): void
    {
        Storage::disk($disk)->put($filename, $content);
    }

    public static function fileExists(string $disk, string $filename): bool
    {
        return Storage::disk($disk)->exists($filename);
    }

    public static function deleteFile(string $disk, string $filename): bool
    {
        return Storage::disk($disk)->delete($filename);
    }

    public static function getFilePath(string $disk, string $filename): string
    {
        return Storage::disk($disk)->path($filename);
    }
}

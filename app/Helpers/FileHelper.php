<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Format file size to human readable format
     */
    public static function formatFileSize($bytes, $precision = 2): string
    {
        if ($bytes == 0)
            return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes) / log(1024));
        return round($bytes / pow(1024, $i), $precision) . ' ' . $units[$i];
    }

    /**
     * Get Bootstrap icon class for file type
     */
    public static function getFileIcon($mimeType, $fileName): string
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Check for MSG files (Outlook email files)
        if (strtolower($extension) === 'msg') {
            return 'envelope';
        }

        // Mime type checks
        if (str_contains($mimeType, 'pdf'))
            return 'file-pdf';
        if (str_contains($mimeType, 'word') || str_contains($mimeType, 'doc') || str_contains($mimeType, 'docx'))
            return 'file-word';
        if (str_contains($mimeType, 'excel') || str_contains($mimeType, 'sheet') || str_contains($mimeType, 'xls') || str_contains($mimeType, 'xlsx'))
            return 'file-excel';
        if (str_contains($mimeType, 'powerpoint') || str_contains($mimeType, 'presentation') || str_contains($mimeType, 'ppt') || str_contains($mimeType, 'pptx'))
            return 'file-ppt';
        if (str_contains($mimeType, 'zip') || str_contains($mimeType, 'rar') || str_contains($mimeType, '7z') || str_contains($mimeType, 'tar') || str_contains($mimeType, 'gz'))
            return 'file-zip';
        if (str_contains($mimeType, 'text') || str_contains($mimeType, 'plain'))
            return 'file-text';
        if (str_contains($mimeType, 'csv'))
            return 'file-earmark-spreadsheet';
        if (str_contains($mimeType, 'json') || str_contains($mimeType, 'xml'))
            return 'file-code';
        if (str_contains($mimeType, 'image'))
            return 'file-image';
        if (str_contains($mimeType, 'video'))
            return 'file-play';
        if (str_contains($mimeType, 'audio'))
            return 'file-music';

        return 'file-earmark';
    }

    /**
     * Get text color class for file type
     */
    public static function getFileColor($mimeType, $fileName): string
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        // MSG files color (Outlook email)
        if (strtolower($extension) === 'msg') {
            return 'text-info';
        }

        // Mime type checks
        if (str_contains($mimeType, 'pdf'))
            return 'text-danger';
        if (str_contains($mimeType, 'word') || str_contains($mimeType, 'doc') || str_contains($mimeType, 'docx'))
            return 'text-primary';
        if (str_contains($mimeType, 'excel') || str_contains($mimeType, 'sheet') || str_contains($mimeType, 'xls') || str_contains($mimeType, 'xlsx'))
            return 'text-success';
        if (str_contains($mimeType, 'powerpoint') || str_contains($mimeType, 'presentation') || str_contains($mimeType, 'ppt') || str_contains($mimeType, 'pptx'))
            return 'text-warning';
        if (str_contains($mimeType, 'zip') || str_contains($mimeType, 'rar') || str_contains($mimeType, '7z') || str_contains($mimeType, 'tar') || str_contains($mimeType, 'gz'))
            return 'text-secondary';
        if (str_contains($mimeType, 'csv'))
            return 'text-success';
        if (str_contains($mimeType, 'json'))
            return 'text-dark';

        return 'text-muted';
    }

    /**
     * Get badge configuration for file type
     */
    public static function getFileBadge($fileName): array
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $badges = [
            'pdf' => ['bg' => 'danger', 'text' => 'PDF'],
            'doc' => ['bg' => 'primary', 'text' => 'DOC'],
            'docx' => ['bg' => 'primary', 'text' => 'DOCX'],
            'xls' => ['bg' => 'success', 'text' => 'XLS'],
            'xlsx' => ['bg' => 'success', 'text' => 'XLSX'],
            'ppt' => ['bg' => 'warning', 'text' => 'PPT'],
            'pptx' => ['bg' => 'warning', 'text' => 'PPTX'],
            'msg' => ['bg' => 'info', 'text' => 'MSG'],
            'txt' => ['bg' => 'secondary', 'text' => 'TXT'],
            'csv' => ['bg' => 'success', 'text' => 'CSV'],
            'zip' => ['bg' => 'secondary', 'text' => 'ZIP'],
            'rar' => ['bg' => 'secondary', 'text' => 'RAR'],
            '7z' => ['bg' => 'secondary', 'text' => '7Z'],
            'json' => ['bg' => 'dark', 'text' => 'JSON'],
        ];

        return $badges[$extension] ?? ['bg' => 'secondary', 'text' => strtoupper($extension) ?: 'FILE'];
    }

    /**
     * Check if file is an image
     */
    public static function isImage($mimeType): bool
    {
        return str_contains($mimeType, 'image');
    }
}

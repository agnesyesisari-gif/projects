<?php

use Config\Paths;

if (!function_exists('upload_path')) {
    /**
     * Get upload path for specific type
     */
    function upload_path(string $type = 'jadwal', bool $absolute = true): ?string
    {
        $paths = new Paths();
        return $paths->getUploadPath($type, $absolute);
    }
}

if (!function_exists('upload_url')) {
    /**
     * Get upload URL for specific type
     */
    function upload_url(string $type = 'jadwal', string $filename = ''): string
    {
        $paths = new Paths();
        $uploadPaths = $paths->getUploadPaths();
        
        if (isset($uploadPaths[$type])) {
            $url = $uploadPaths[$type]['url'];
            return $filename ? $url . '/' . $filename : $url;
        }
        
        return base_url('uploads/' . $type);
    }
}

if (!function_exists('backup_path')) {
    /**
     * Get backup path
     */
    function backup_path(string $filename = ''): string
    {
        $paths = new Paths();
        $path = $paths->getBackupPath();
        return $filename ? $path . '/' . $filename : $path;
    }
}

if (!function_exists('laporan_path')) {
    /**
     * Get laporan path
     */
    function laporan_path(string $filename = ''): string
    {
        $paths = new Paths();
        $path = $paths->getLaporanPath();
        return $filename ? $path . '/' . $filename : $path;
    }
}

if (!function_exists('template_path')) {
    /**
     * Get template path
     */
    function template_path(string $filename = ''): string
    {
        $paths = new Paths();
        $path = $paths->getTemplatePath();
        return $filename ? $path . '/' . $filename : $path;
    }
}

if (!function_exists('check_directories')) {
    /**
     * Check directory permissions
     */
    function check_directories(): array
    {
        $paths = new Paths();
        return $paths->checkDirectoryPermissions();
    }
}

if (!function_exists('create_app_directories')) {
    /**
     * Create all application directories
     */
    function create_app_directories(): void
    {
        $paths = new Paths();
        $paths->createDirectories();
    }
}
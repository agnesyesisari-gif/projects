<?php

namespace Config;

class Paths
{
    public string $systemDirectory   = __DIR__ . '/../../system';
    public string $appDirectory      = __DIR__ . '/..';
    public string $writableDirectory = __DIR__ . '/../../writable';
    public $testsDirectory = __DIR__ . '/../../tests';
    public $viewDirectory  = __DIR__ . '/../Views';
    public $jadwalUploadPath = __DIR__ . '/../../public/uploads/jadwal';
    public $programUploadPath = __DIR__ . '/../../public/uploads/program';
    public $dokumenUploadPath = __DIR__ . '/../../public/uploads/dokumen';
    public $fotoUploadPath = __DIR__ . '/../../public/uploads/foto';
    public $backupPath = __DIR__ . '/../../writable/backups';
    public $laporanPath = __DIR__ . '/../../writable/laporan';
    public $templatePath = __DIR__ . '/../../writable/templates';
    public $cacheViewPath = __DIR__ . '/../../writable/cache/views';
    public $userLogsPath = __DIR__ . '/../../writable/logs/user';
    public $systemLogsPath = __DIR__ . '/../../writable/logs/system';

    public function getJadwalUploadPath(): string
    {
        return $this->jadwalUploadPath;
    }

    public function getJadwalUploadRelativePath(): string
    {
        return '/uploads/jadwal';
    }

    public function getProgramUploadPath(): string
    {
        return $this->programUploadPath;
    }

    public function getProgramUploadRelativePath(): string
    {
        return '/uploads/program';
    }

    public function getDokumenUploadPath(): string
    {
        return $this->dokumenUploadPath;
    }

    public function getFotoUploadPath(): string
    {
        return $this->fotoUploadPath;
    }

    public function getBackupPath(): string
    {
        return $this->backupPath;
    }

    public function getLaporanPath(): string
    {
        return $this->laporanPath;
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    public function createDirectories(): void
    {
        $directories = [
            $this->writableDirectory,
            $this->jadwalUploadPath,
            $this->programUploadPath,
            $this->dokumenUploadPath,
            $this->fotoUploadPath,
            $this->backupPath,
            $this->laporanPath,
            $this->templatePath,
            $this->cacheViewPath,
            $this->userLogsPath,
            $this->systemLogsPath,
            $this->writableDirectory . '/cache',
            $this->writableDirectory . '/logs',
            $this->writableDirectory . '/session',
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
        }
    }

    public function checkDirectoryPermissions(): array
    {
        $directories = [
            'writable' => $this->writableDirectory,
            'jadwal_upload' => $this->jadwalUploadPath,
            'program_upload' => $this->programUploadPath,
            'dokumen_upload' => $this->dokumenUploadPath,
            'foto_upload' => $this->fotoUploadPath,
            'backup' => $this->backupPath,
            'laporan' => $this->laporanPath,
            'template' => $this->templatePath,
            'cache_view' => $this->cacheViewPath,
            'user_logs' => $this->userLogsPath,
            'system_logs' => $this->systemLogsPath,
        ];

        $results = [];
        foreach ($directories as $key => $path) {
            // Create directory if doesn't exist
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $results[$key] = [
                'path' => $path,
                'exists' => is_dir($path),
                'writable' => is_writable($path),
                'permission' => substr(sprintf('%o', fileperms($path)), -4),
                'owner' => function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($path))['name'] : 'Unknown',
                'group' => function_exists('posix_getgrgid') ? posix_getgrgid(filegroup($path))['name'] : 'Unknown',
            ];
        }

        return $results;
    }

    public function getUploadPaths(): array
    {
        return [
            'jadwal' => [
                'absolute' => $this->jadwalUploadPath,
                'relative' => $this->getJadwalUploadRelativePath(),
                'url' => base_url('uploads/jadwal'),
                'max_size' => 5242880, // 5MB
                'allowed_types' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'],
            ],
            'program' => [
                'absolute' => $this->programUploadPath,
                'relative' => $this->getProgramUploadRelativePath(),
                'url' => base_url('uploads/program'),
                'max_size' => 5242880,
                'allowed_types' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
            ],
            'dokumen' => [
                'absolute' => $this->dokumenUploadPath,
                'relative' => '/uploads/dokumen',
                'url' => base_url('uploads/dokumen'),
                'max_size' => 10485760, // 10MB
                'allowed_types' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
            ],
            'foto' => [
                'absolute' => $this->fotoUploadPath,
                'relative' => '/uploads/foto',
                'url' => base_url('uploads/foto'),
                'max_size' => 5242880,
                'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
            ],
        ];
    }

    public function getUploadPath(string $type, bool $absolute = true): ?string
    {
        $paths = $this->getUploadPaths();
        
        if (isset($paths[$type])) {
            return $absolute ? $paths[$type]['absolute'] : $paths[$type]['relative'];
        }
        
        return null;
    }

    public function getAllowedTypes(string $type): array
    {
        $paths = $this->getUploadPaths();
        return $paths[$type]['allowed_types'] ?? [];
    }

    public function getMaxSize(string $type): int
    {
        $paths = $this->getUploadPaths();
        return $paths[$type]['max_size'] ?? 5242880; // Default 5MB
    }
}
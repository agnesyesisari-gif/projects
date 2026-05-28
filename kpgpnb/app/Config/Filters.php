<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthFilter;
use App\Filters\AdminFilter;

class Filters extends BaseFilters
{
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        // Custom filters
        'auth'          => AuthFilter::class,
        'admin'         => AdminFilter::class,
    ];

    public $globals = [
        'before' => [
            'csrf' => ['except' => ['api/*']],
            // 'honeypot',
            // 'secureheaders',
        ],
        'after' => [
            'toolbar',
            // 'pagecache',
            // 'performance',
        ],
    ];

    public $methods = [];

    public $filters = [
        'auth' => [
            'before' => ['admin/*', 'dashboard/*']
        ],
        'admin' => [
            'before' => ['admin/ibadah/*', 'admin/program/*']
        ]
    ];
}
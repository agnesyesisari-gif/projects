<?php

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

chdir(FCPATH);

$pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
$paths = new Config\Paths();

require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

$app->run();
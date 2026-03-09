<?php
// ThinkCMF5 entry file.
namespace think;

define('APP_DEBUG', true);
define('CMF_ROOT', dirname(__DIR__) . '/');
define('CMF_DATA', CMF_ROOT . 'data/');
define('APP_PATH', CMF_ROOT . 'app/');
define('WEB_ROOT', __DIR__ . '/');

require CMF_ROOT . 'vendor/thinkphp/base.php';

Container::get('app', [APP_PATH])->run()->send();

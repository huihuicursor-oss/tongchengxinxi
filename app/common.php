<?php
// 应用公共文件

if (!function_exists('app_base_url')) {
    function app_base_url(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $base = preg_replace('#/index\.php$#', '', $scriptName) ?? '';
        $base = rtrim($base, '/');

        return $base === '/' ? '' : $base;
    }
}

if (!function_exists('app_url_path')) {
    function app_url_path(string $path = ''): string
    {
        $base = app_base_url();
        $path = ltrim($path, '/');

        if ($path === '') {
            return $base !== '' ? $base : '/';
        }

        return ($base !== '' ? $base : '') . '/' . $path;
    }
}

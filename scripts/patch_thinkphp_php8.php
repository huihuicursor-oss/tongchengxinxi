<?php

$root = dirname(__DIR__);
$targetDir = $root . '/thinkphp/library/think';
if (!is_dir($targetDir) || PHP_VERSION_ID < 80000) {
    exit(0);
}

$files = [
    'Container.php'                 => ['offsetExists', 'offsetGet', 'offsetSet', 'offsetUnset', 'count', 'getIterator'],
    'Config.php'                    => ['offsetSet', 'offsetExists', 'offsetUnset', 'offsetGet'],
    'Collection.php'                => ['offsetExists', 'offsetGet', 'offsetSet', 'offsetUnset', 'count', 'getIterator', 'jsonSerialize'],
    'Paginator.php'                 => ['offsetExists', 'offsetGet', 'offsetSet', 'offsetUnset', 'count', 'getIterator', 'jsonSerialize'],
    'Model.php'                     => ['offsetSet', 'offsetExists', 'offsetUnset', 'offsetGet'],
    'db/Where.php'                  => ['offsetSet', 'offsetExists', 'offsetUnset', 'offsetGet'],
    'model/concern/Conversion.php'  => ['jsonSerialize'],
];

foreach ($files as $relative => $methods) {
    $path = $targetDir . '/' . $relative;
    if (!is_file($path)) {
        continue;
    }
    $content = file_get_contents($path);
    foreach ($methods as $method) {
        $content = preg_replace(
            '/(\n\s*)(public function ' . preg_quote($method, '/') . '\s*\()/m',
            "$1#[\\ReturnTypeWillChange]$1$2",
            $content,
            1
        );
    }
    file_put_contents($path, $content);
}

$loaderPath = $targetDir . '/Loader.php';
if (is_file($loaderPath)) {
    $content = file_get_contents($loaderPath);
    $content = str_replace(
        "    public static function parseName(\$name, \$type = 0, \$ucfirst = true)\n    {\n        if (\$type) {\n",
        "    public static function parseName(\$name, \$type = 0, \$ucfirst = true)\n    {\n        \$name = (string) \$name;\n\n        if (\$type) {\n",
        $content
    );
    file_put_contents($loaderPath, $content);
}

$requestPath = $targetDir . '/Request.php';
if (is_file($requestPath)) {
    $content = file_get_contents($requestPath);
    $content = str_replace(
        "$this->host = \$this->server('HTTP_X_REAL_HOST') ?: \$this->server('HTTP_X_FORWARDED_HOST') ?: \$this->server('HTTP_HOST');",
        "$this->host = (string) (\$this->server('HTTP_X_REAL_HOST') ?: \$this->server('HTTP_X_FORWARDED_HOST') ?: \$this->server('HTTP_HOST') ?: '');",
        $content
    );
    file_put_contents($requestPath, $content);
}

<?php

namespace Utyemma\LaraSetting\Support;

use Illuminate\Support\Collection;
use ReflectionClass;

class DiscoverClasses {

    public static function find(string $namespace, string $directory): array {
        return collect(self::getFilesAndDirectories($directory))
            ->map(function ($path) use ($namespace, $directory) {
                if (is_dir($path)) {
                    $subNamespace = $namespace . '\\' . basename($path);
                    return self::find($subNamespace, $path);
                }

                if (is_file($path) && str_ends_with($path, '.php')) {
                    $className = implode('', [$namespace, '\\', basename($path, '.php')]);

                    if (class_exists($className)) {
                        $reflection = new ReflectionClass($className);
                        if ($reflection->isInstantiable()) {
                            return $className;
                        }
                    }
                }
            })->toArray();
    }

    private static function getFilesAndDirectories(string $directory): array {
        return glob($directory . '/*');
    }
}

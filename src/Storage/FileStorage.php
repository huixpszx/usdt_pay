<?php

namespace UsdtPay\Storage;

class FileStorage implements StorageInterface
{
    private string $baseDir;

    public function __construct(string $baseDir)
    {
        $this->baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR);
        if (!is_dir($this->baseDir)) {
            mkdir($this->baseDir, 0777, true);
        }
    }

    public function read(string $key): array
    {
        $path = $this->pathForKey($key);
        if (!is_file($path)) {
            return [];
        }
        $raw = file_get_contents($path);
        $data = json_decode($raw ?: '[]', true);
        return is_array($data) ? $data : [];
    }

    public function write(string $key, array $data): void
    {
        $path = $this->pathForKey($key);
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    private function pathForKey(string $key): string
    {
        $safe = str_replace(['..', ':', '\\'], ['.', '-', '/'], $key);
        return $this->baseDir . DIRECTORY_SEPARATOR . $safe . '.json';
    }
}

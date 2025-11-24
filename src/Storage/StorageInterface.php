<?php

namespace UsdtPay\Storage;

interface StorageInterface
{
    public function read(string $key): array;
    public function write(string $key, array $data): void;
}

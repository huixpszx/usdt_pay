<?php

namespace UsdtPay;

use UsdtPay\Storage\StorageInterface;

class AmountAllocator
{
    private Config $config;
    private StorageInterface $storage;

    public function __construct(Config $config, StorageInterface $storage)
    {
        $this->config = $config;
        $this->storage = $storage;
    }

    public function allocate(): float
    {
        $base = $this->config->getDailyAmount();
        if (!$this->config->isUniqueSuffix()) {
            return $base;
        }
        $dateKey = 'amounts/' . date('Y-m-d');
        $data = $this->storage->read($dateKey);
        $used = $data['used'] ?? [];
        $precision = $this->config->getSuffixPrecision();
        $max = pow(10, $precision) - 1;
        $next = 1;
        if (!empty($used)) {
            $next = max($used) + 1;
        }
        if ($next > $max) {
            throw new \RuntimeException('今日可分配小数已用尽');
        }
        $used[] = $next;
        $this->storage->write($dateKey, ['used' => $used]);
        $suffix = $next / pow(10, $precision);
        return round($base + $suffix, $precision);
    }
}

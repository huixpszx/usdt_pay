<?php

namespace UsdtPay;

class Config
{
    private array $addresses;
    private float $dailyAmount;
    private bool $uniqueSuffix;
    private int $suffixPrecision;
    private string $storageDir;
    private string $title;
    private string $usdtContract;
    private string $tronApiBase;

    public function __construct(array $data)
    {
        $this->addresses = $data['addresses'] ?? [];
        $this->dailyAmount = (float)($data['daily_amount'] ?? 0);
        $this->uniqueSuffix = (bool)($data['unique_suffix'] ?? false);
        $this->suffixPrecision = (int)($data['suffix_precision'] ?? 4);
        $this->storageDir = $data['storage_dir'] ?? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'usdt_pay';
        $this->title = $data['title'] ?? 'USDT 支付';
        $this->usdtContract = $data['usdt_contract'] ?? 'TR7NHqjeKQxGtcAM9VYcAzhorowC3eWgLzx';
        $this->tronApiBase = $data['tron_api_base'] ?? 'https://apilist.tronscanapi.com';
    }

    public function getAddresses(): array
    {
        return $this->addresses;
    }

    public function getDailyAmount(): float
    {
        return $this->dailyAmount;
    }

    public function isUniqueSuffix(): bool
    {
        return $this->uniqueSuffix;
    }

    public function getSuffixPrecision(): int
    {
        return $this->suffixPrecision;
    }

    public function getStorageDir(): string
    {
        return $this->storageDir;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUsdtContract(): string
    {
        return $this->usdtContract;
    }

    public function getTronApiBase(): string
    {
        return $this->tronApiBase;
    }
}

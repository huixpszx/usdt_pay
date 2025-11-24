<?php

namespace UsdtPay;

use UsdtPay\Storage\FileStorage;

class UsdtPay
{
    private Config $config;
    private FileStorage $storage;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
        $this->storage = new FileStorage($this->config->getStorageDir());
    }

    public function allocatePayment(?string $address = null): array
    {
        $addr = $address ?: ($this->config->getAddresses()[0] ?? '');
        if (!$addr) {
            throw new \InvalidArgumentException('缺少地址');
        }
        $allocator = new AmountAllocator($this->config, $this->storage);
        $amount = $allocator->allocate();
        $renderer = new PaymentPageRenderer();
        $html = $renderer->render($this->config->getTitle(), $addr, $amount);
        return ['address' => $addr, 'amount' => $amount, 'html' => $html];
    }

    public function subscribe(callable $onReceive, int $intervalSeconds = 15, int $maxIterations = 0): void
    {
        $sub = new SubscriptionService($this->config, $this->storage);
        $sub->run($onReceive, $intervalSeconds, $maxIterations);
    }
}

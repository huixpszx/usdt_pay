<?php

namespace UsdtPay;

use UsdtPay\Storage\StorageInterface;
use UsdtPay\Tron\TronClient;

class SubscriptionService
{
    private Config $config;
    private StorageInterface $storage;
    private TronClient $tron;

    public function __construct(Config $config, StorageInterface $storage)
    {
        $this->config = $config;
        $this->storage = $storage;
        $this->tron = new TronClient($config->getTronApiBase(), $config->getUsdtContract());
    }

    public function fetchNewEvents(callable $onReceive): int
    {
        $latest = 0;
        foreach ($this->config->getAddresses() as $addr) {
            $key = 'events/' . $addr;
            $state = $this->storage->read($key);
            $minTs = (int)($state['last_ts'] ?? 0);
            $events = $this->tron->fetchIncoming($addr, $minTs);
            foreach ($events as $e) {
                $onReceive($e);
                if ($e['timestamp'] > $latest) {
                    $latest = $e['timestamp'];
                }
            }
            $newTs = empty($events) ? $minTs : max(array_column($events, 'timestamp'));
            $this->storage->write($key, ['last_ts' => $newTs]);
        }
        return $latest;
    }

    public function run(callable $onReceive, int $intervalSeconds = 15, int $maxIterations = 0): void
    {
        $i = 0;
        while (true) {
            $this->fetchNewEvents($onReceive);
            $i++;
            if ($maxIterations > 0 && $i >= $maxIterations) {
                break;
            }
            sleep($intervalSeconds);
        }
    }
}

<?php

namespace UsdtPay\Tron;

class TronClient
{
    private string $base;
    private string $usdtContract;

    public function __construct(string $base, string $usdtContract)
    {
        $this->base = rtrim($base, '/');
        $this->usdtContract = $usdtContract;
    }

    public function fetchIncoming(string $address, int $minTs): array
    {
        $url = $this->base . '/ocean/v2/trc20/transfer?limit=50&contract=' . urlencode($this->usdtContract) . '&relatedAddress=' . urlencode($address);
        $raw = $this->httpGet($url);
        $json = json_decode($raw ?: '{}', true);
        $list = $json['data'] ?? [];
        $out = [];
        foreach ($list as $it) {
            $ts = (int)($it['block_ts'] ?? 0);
            if ($ts <= $minTs) {
                continue;
            }
            if (($it['to_address'] ?? '') !== $address) {
                continue;
            }
            $out[] = [
                'tx' => $it['transaction_id'] ?? '',
                'from' => $it['from_address'] ?? '',
                'to' => $it['to_address'] ?? '',
                'amount' => isset($it['value']) ? ((float)$it['value']) : 0.0,
                'timestamp' => $ts,
            ];
        }
        usort($out, function ($a, $b) { return $a['timestamp'] <=> $b['timestamp']; });
        return $out;
    }

    private function httpGet(string $url): string
    {
        $ctx = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
                'header' => "User-Agent: usdt-pay\r\nAccept: application/json"
            ]
        ]);
        $res = @file_get_contents($url, false, $ctx);
        return $res !== false ? $res : '';
    }
}

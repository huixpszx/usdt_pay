<?php

namespace UsdtPay;

class PaymentPageRenderer
{
    public function render(string $title, string $address, float $amount): string
    {
        $amt = number_format($amount, 4, '.', '');
        $addr = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
        $ttl = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        return '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
            . '<title>' . $ttl . '</title>'
            . '<link rel="preconnect" href="https://cdnjs.cloudflare.com">'
            . '<style>'
            . 'body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu;display:flex;justify-content:center;align-items:center;min-height:100vh;background:#0f172a;color:#e2e8f0;margin:0}'
            . '.card{background:#111827;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.4);padding:24px;width:360px}'
            . '.title{font-size:20px;margin-bottom:12px}'
            . '.amount{font-size:32px;font-weight:700;letter-spacing:.5px;margin:12px 0}'
            . '.row{display:flex;gap:8px;align-items:center;margin:8px 0}'
            . '.address{flex:1;background:#0b1220;border:1px solid #1f2937;border-radius:12px;padding:10px;word-break:break-all}'
            . '.btn{background:#2563eb;border:none;color:#fff;padding:10px 14px;border-radius:10px;cursor:pointer}'
            . '.btn:active{transform:scale(.98)}'
            . '.qr{display:flex;justify-content:center;margin:16px 0}'
            . '.hint{font-size:12px;color:#94a3b8;text-align:center;margin-top:6px}'
            . '</style>'
            . '</head><body>'
            . '<div class="card">'
            . '<div class="title">' . $ttl . '</div>'
            . '<div class="amount">' . $amt . ' USDT</div>'
            . '<div class="row"><div class="address" id="addr">' . $addr . '</div>'
            . '<button class="btn" id="copyAddr">复制地址</button></div>'
            . '<div class="row"><div class="address" id="amt">' . $amt . '</div>'
            . '<button class="btn" id="copyAmt">复制金额</button></div>'
            . '<div class="qr"><div id="qrcode"></div></div>'
            . '<div class="hint">网络 TRON (TRC20) USDT</div>'
            . '</div>'
            . '<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>'
            . '<script>'
            . 'var a=document.getElementById("addr").textContent;'
            . 'new QRCode(document.getElementById("qrcode"),{text:a,width:180,height:180});'
            . 'document.getElementById("copyAddr").onclick=function(){navigator.clipboard.writeText(a)};'
            . 'var m=document.getElementById("amt").textContent;'
            . 'document.getElementById("copyAmt").onclick=function(){navigator.clipboard.writeText(m)};'
            . '</script>'
            . '</body></html>';
    }
}

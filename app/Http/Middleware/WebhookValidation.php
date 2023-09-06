<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $secretKey = config('services.autotrader.webhook_secret');
        $signatureHeader = $request->header('AutoTrader-Signature');

        $signatureParts = explode(',', $signatureHeader);
        $t = null;
        $v1 = null;
        foreach ($signatureParts as $part) {
            $part = trim($part);
            if (strpos($part, 't=') === 0) {
                $t = substr($part, 2);
            } elseif (strpos($part, 'v1=') === 0) {
                $v1 = substr($part, 3);
            }
        }

        if (!$t || !$v1) {
            return response()->json(['error' => 'Invalid signature format'], 401);
        }

        if (!$this->validateSignature($secretKey, $t, $v1, $request->getContent())) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        Log::info('Webhook validate successfully');

        return $next($request);
    }

    private function validateSignature($secretKey, $timestamp, $clientSignature, $body)
    {
        $expectedSignature = hash_hmac('sha256', $timestamp . '.' . $body, $secretKey);

        return hash_equals($expectedSignature, $clientSignature);
    }
}

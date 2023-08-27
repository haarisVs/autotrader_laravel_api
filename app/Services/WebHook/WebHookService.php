<?php

namespace App\Services\WebHook;

;
use App\Models\Webhook;
use Illuminate\Support\Facades\Log;
use App\Services\Stock\StockService;
class WebHookService
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    public function index($request)
    {
        $payload = $request->getContent();
        $payload_array = json_decode($payload, true);
        $this->store($payload_array);
        return $this->stockService->update($payload_array);
    }

    public function store($payload)
    {
        Webhook::create([
            'webhook_id' => $payload['id'],
            'type' => $payload['type'],
            'body' => $payload,
        ]);
        Log::info('Webhook event saved successfully', ['webhook_id' => $payload['id']]);
    }

    private function validateSignature($secretKey, $timestamp, $clientSignature, $body)
    {
        $expectedSignature = hash_hmac('sha256', $timestamp . '.' . $body, $secretKey);

        return hash_equals($expectedSignature, $this->extractV1Value($clientSignature));
    }

    private function extractV1Value($clientSignature)
    {
        $parts = explode(',', $clientSignature);
        foreach ($parts as $part) {
            if (strpos($part, 'v1=') !== false) {
                return substr($part, 3);
            }
        }
        return '';
    }

}

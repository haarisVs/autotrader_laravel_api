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
}

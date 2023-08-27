<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Services\WebHook\WebHookService;


class WebhookController extends Controller
{
    protected $webhookService;
    function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }
    public function update(Request $request)
    {
        $response = $this->webhookService->index($request);
        return response()->json($response);
    }
}

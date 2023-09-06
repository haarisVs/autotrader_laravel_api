<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Stock\StockService;
use Illuminate\Support\Facades\Cache;

class StockController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $payload = $this->stockService->index($request);
        return response()->json($payload);
    }

    public function show()
    {
        $payload = $this->stockService->show();
        return response()->json($payload);
    }

    public function findStockDetail($searchId)
    {
        $payload = $this->stockService->findBy($searchId);
        return response()->json($payload);
    }
}

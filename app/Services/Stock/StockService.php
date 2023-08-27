<?php

namespace App\Services\Stock;

use App\Repositories\Stock\StockRepository;
class StockService
{
    protected $stockRepository;

    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }
    public function index($request)
    {
        return $this->stockRepository->findAll($request);
    }

    public function show()
    {
        return $this->stockRepository->findAllLocal();
    }

    public function findBy($stockId)
    {
        return $this->stockRepository->findById($stockId);
    }

    public function update($payload)
    {
        if ($payload['type'] === 'STOCK_UPDATE')
        {
            $webhook_id = $payload['id'];
            return $this->stockRepository->update($webhook_id, $payload);
        }
    }


}

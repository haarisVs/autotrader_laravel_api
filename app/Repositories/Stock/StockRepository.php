<?php

namespace App\Repositories\Stock;

use App\Repositories\Interface\StockInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;

class StockRepository implements StockInterface
{
    /**
     * @param $params
     * @return mixed
     */
    public function findAll($request)
    {
        $token = Cache::get('autotrader_authenticated');
        $page = Cache::get('last_processed_page', 1);
        $pageSize = 200; // Adjust as needed

        set_time_limit(700);

        do {
            $queryParams = [
                'page' => $page,
                'pageSize' => $pageSize,
                // Add more parameters as needed
            ];

            $queryString = http_build_query($queryParams);

            $baseApiUrl = 'https://api-sandbox.autotrader.co.uk';
            $stockEndpoint = "{$baseApiUrl}/stock?{$queryString}";

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get($stockEndpoint);

            if ($response->failed()) {
                return response()->json(['error' => 'API request failed'], 500);
            }

            $batch = $response->json()['results'];
            $totalResults = $response->json()['totalResults'];
            $totalPages = ceil($totalResults / $pageSize);

            if (!empty($batch)) {
                $this->create($batch);
            }

            $page++;
            Cache::put('last_processed_page', $page, now()->addMinutes(60));

        } while ($page <= $totalPages);

        return response()->json(['message' => 'Stock data fetched successfully', "totalpage" => $totalPages, 'currentpage' =>  $page], 200);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        foreach ($attributes as $record) {
            Stock::Create(
             [
                'vehicle' => $record['vehicle'],
                'advertiser' => $record['advertiser'],
                'adverts' => $record['adverts'],
                'metadata' => $record['metadata'],
                'stockId' => $record['metadata']['stockId'],
                'searchId' => $record['metadata']['searchId'],
                'features' => $record['features'],
                'media' => $record['media'],
                'history' => $record['history'],
                'check' => $record['check'],
            ]);
        }

    }

    /**
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        $stock = Stock::where('stockId', $id)->first();
        if ($stock)
        {
            $stock->update(['vehicle' => $attributes['data']['vehicle']]);
            Log::info('Stock record updated successfully', ['stockId' => $id]);
            $response = [
                'message' => 'Stock record updated successfully',
                'id' => $id
            ];
        }
        else
        {
            Log::warning('Stock record not found', ['stockId' => $id]);
            $response = [
                'message' => 'Stock record not found',
                'id' => $id
            ];
        }
        return $response;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        // Find the stock record based on the given stockId
        $stock = Stock::where('stockId', $id)->first();

        if (!$stock) {
            return response()->json(['message' => 'Stock not found'], 404);
        }
        return response()->json($stock);
    }

    /**
     * @return mixed
     */
    public function findAllLocal()
    {
        return Stock::paginate(20);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}

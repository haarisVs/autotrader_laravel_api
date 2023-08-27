<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AutotraderAuthentication
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
        if (Cache::has('autotrader_authenticated')) {
            return $next($request);
        }

//        // Data to send in the request
//        $data = [
//            'key' => 'WebNestLtd-DealerWebsiteSV-SB-26-07-23',
//            'secret' => 'ZpZSw4pCzTMZ1FFSeKJi0RmMHCg6tRx2',
//        ];
        $data = [
            'key' => config('services.autotrader.api_key'),
            'secret' => config('services.autotrader.api_secret')
        ];

        $response = Http::post('https://api-sandbox.autotrader.co.uk/authenticate', $data);
        if ($response->failed())
        {
            return response()->json(['error' => 'API request failed', 'res' => $response], 500);
        }

        $response_data = $response->json();
        if (isset($response_data['access_token']))
        {
            $request->headers->set('AutotradersToken', 'Bearer ' . $response_data['access_token']);
            // Store the authentication status in cache for a certain duration (e.g., 15 minutes)
            Cache::put('autotrader_authenticated', 'Bearer ' . $response_data['access_token'], now()->addMinutes(15));
        }

        return $next($request);
    }
}

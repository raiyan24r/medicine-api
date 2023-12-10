<?php

namespace App\Clients;

use App\Constants\Endpoints;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DrugClient
{
    private string $base_url;
    private int $cacheExpiration;
    private const DETAILS_CACHE_KEY = 'drug_details_';
    private const HISTORY_STATUS_CACHE_KEY = 'drug_history_status_';

    public function __construct()
    {
        $this->base_url = config('app.drugs_api');
        $this->cacheExpiration = config('app.cache_expiration') * 60;
    }

    public function searchByName(string $name): array
    {
        return Cache::remember($this::DETAILS_CACHE_KEY.$name, $this->cacheExpiration, function () use($name) {
            $parameters = [
                'name' => $name
            ];
            $url = $this->base_url . Endpoints::GET_DRUGS . '?' . http_build_query($parameters);

            $response = Http::get($url);
            return $response->json();
        });
    }

    public function historyStatusByRxcui(string $rxcui): array
    {
        return Cache::remember($this::HISTORY_STATUS_CACHE_KEY.$rxcui, $this->cacheExpiration, function () use($rxcui) {
            $url = $this->base_url . sprintf(Endpoints::GET_HISTORY_BY_RXCUI, $rxcui);

            $response = Http::get($url);
            return $response->json();
        });
    }
}

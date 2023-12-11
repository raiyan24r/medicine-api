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

    /**
     * Search for drug details by name, utilizing caching for optimization.
     *
     * @param string $name The name of the drug for which details are to be retrieved.
     *
     * @return array An array containing drug details based on the provided name.
     */
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

    /**
     * Get history status for a drug based on the provided rxcui, utilizing caching for optimization.
     *
     * @param string $rxcui The rxcui (RxNorm Concept Unique Identifier) of the drug.
     *
     * @return array An array containing the history status for the drug based on the provided rxcui.
     */
    public function historyStatusByRxcui(string $rxcui): array
    {
        return Cache::remember($this::HISTORY_STATUS_CACHE_KEY.$rxcui, $this->cacheExpiration, function () use($rxcui) {
            $url = $this->base_url . sprintf(Endpoints::GET_HISTORY_BY_RXCUI, $rxcui);

            $response = Http::get($url);
            return $response->json();
        });
    }
}

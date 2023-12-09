<?php

namespace app\Clients;

use App\Http\Endpoints;
use Illuminate\Support\Facades\Http;

class DrugClient
{
    private $base_url;

    function __construct() {
        $this->base_url = config('app.drugs_api');
    }
    public function searchByKeyword(string $keyword) {

        $parameters = [
            'name' => $keyword
        ];
        $url = $this->base_url . Endpoints::GET_DRUGS . '?' . http_build_query($parameters);

        $response = Http::get($url);
        return $response->json();
    }
}

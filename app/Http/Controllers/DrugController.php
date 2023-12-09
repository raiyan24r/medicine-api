<?php

namespace App\Http\Controllers;

use app\Clients\DrugClient;

class DrugController extends Controller
{
    public function __construct(private DrugClient $drugClient) {

    }
    public function search() {
       $test = $this->drugClient->searchByKeyword('Lipitor');

       return $test;
    }
}

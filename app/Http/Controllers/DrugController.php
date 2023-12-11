<?php

namespace App\Http\Controllers;

use App\Services\DrugSearchServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Http\Helpers\httpResponse;

class DrugController extends Controller
{
    public function __construct(private readonly DrugSearchServiceInterface $drugSearchService)
    {
    }

    /**
     * Search for drug details based on the provided drug name.
     *
     * @param Request $request The request object containing the drug name.
     *
     * @return JsonResponse The JSON response containing drug details if found, or an error message if not found or an error occurred.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $drugName = $request->input('drug_name');
            $drugSearchResult = $this->drugSearchService->drugDetails($drugName);
            if (!empty($drugSearchResult)) {
                return httpResponse(200, 'Drug details found', $drugSearchResult);
            } else {
                return httpResponse(404, 'Drug details not found', []);
            }
        } catch (\Exception $e) {
            return httpResponse(500, 'Error occurred', [$e]);
        }
    }
}

<?php

namespace App\Services;
use App\Clients\DrugClient;

class DrugSearchService implements DrugSearchServiceInterface
{
    public function __construct(private readonly DrugClient $drugClient, private readonly FormatMedicineApiServiceInterface $formatMedicineApiService)
    {
    }

    /**
     * Get drug details based on the provided drug name.
     *
     * @param string $name The name of the drug for which details are to be retrieved.
     *
     * @return array An array containing details of the drug, including RxCUI, name, base names, and dose form group names.
     *               Returns an empty array if no drug details are found.
     */
    public function drugDetails(string $name): array
    {
        $drugInfo = $this->drugClient->searchByName($name);
        if (isset($drugInfo['drugGroup']['conceptGroup'])) {
            $sbdDrugProperties = array_slice($this->formatMedicineApiService->findArrayByTty($drugInfo['drugGroup']['conceptGroup'], 'SBD')['conceptProperties'], 0, 5, true);
        } else {
            return [];
        }

        $drugDetails = [];
        foreach($sbdDrugProperties as $drugProperty) {
            $drugHistoryStatus = $this->drugClient->historyStatusByRxcui($drugProperty['rxcui']);
            list($baseNames, $doseFormGroupNames) = $this->formatMedicineApiService->extractBaseNameAndDoseForm($drugHistoryStatus);
            $drugDetails[] = [
                'rxcui' => $drugProperty['rxcui'],
                'name' => $drugProperty['name'],
                'baseNames' => $baseNames,
                'doseFormGroupNames' => $doseFormGroupNames
            ];
        }

        return $drugDetails;
    }
}

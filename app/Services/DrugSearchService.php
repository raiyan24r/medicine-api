<?php

namespace App\Services;
use App\Clients\DrugClient;

class DrugSearchService implements DrugSearchServiceInterface
{
    public function __construct(private readonly DrugClient $drugClient)
    {
    }
    public function drugDetails(string $name): array
    {
        $drugInfo = $this->drugClient->searchByName($name);
        if (isset($drugInfo['drugGroup']['conceptGroup'])) {
            $sbdDrugProperties = array_slice($this->findArrayByTty($drugInfo['drugGroup']['conceptGroup'], 'SBD')['conceptProperties'], 0, 5, true);
        } else {
            return [];
        }

        $drugDetails = [];
        foreach($sbdDrugProperties as $drugProperty) {
            $drugHistoryStatus = $this->drugClient->historyStatusByRxcui($drugProperty['rxcui']);
            list($baseNames, $doseFormGroupNames) = $this->extractBaseNameAndDoseForm($drugHistoryStatus);
            $drugDetails[] = [
                'rxcui' => $drugProperty['rxcui'],
                'name' => $drugProperty['name'],
                'baseNames' => $baseNames,
                'doseFormGroupNames' => $doseFormGroupNames
            ];
        }

        return $drugDetails;
    }

    private function findArrayByTty(array $drugsArray,string $tty = 'SBD'): array
    {
        $resultArray = array_filter($drugsArray, function ($subarray) use ($tty) {
            return isset($subarray["tty"]) && $subarray["tty"] === $tty;
        });

        return $resultArray ? reset($resultArray) : [];
    }

    private function extractBaseNameAndDoseForm(array $drugHistoryStatus): array
    {
        $baseNames = [];
        $doseFormGroupNames = [];

        if (isset($drugHistoryStatus['rxcuiStatusHistory']['definitionalFeatures']['ingredientAndStrength'])) {
            foreach ($drugHistoryStatus['rxcuiStatusHistory']['definitionalFeatures']['ingredientAndStrength'] as $ingredient) {
                $baseNames[] = $ingredient['baseName'];
            }
        }

        if (isset($drugHistoryStatus['rxcuiStatusHistory']['definitionalFeatures']['doseFormGroupConcept'])) {
            foreach ($drugHistoryStatus['rxcuiStatusHistory']['definitionalFeatures']['doseFormGroupConcept'] as $doseFormGroup) {
                $doseFormGroupNames[] = $doseFormGroup['doseFormGroupName'];
            }
        }

        return [$baseNames, $doseFormGroupNames];
    }
}

<?php

namespace App\Services;

class FormatMedicineApiService implements FormatMedicineApiServiceInterface
{
    public function findArrayByTty(array $drugsArray, string $tty = 'SBD'): array
    {
        $resultArray = array_filter($drugsArray, function ($subarray) use ($tty) {
            return isset($subarray["tty"]) && $subarray["tty"] === $tty;
        });

        return $resultArray ? reset($resultArray) : [];
    }

    public function extractBaseNameAndDoseForm(array $drugHistoryStatus): array
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

<?php

namespace App\Services;

class FormatMedicineApiService implements FormatMedicineApiServiceInterface
{
    /**
     * Find an array within a collection of drugs based on the specified term type (TTY).
     *
     * @param array $drugsArray The array of drugs to search within.
     * @param string $tty The term type to filter by (default is 'SBD').
     *
     * @return array The first array that matches the specified term type, or an empty array if not found.
     */
    public function findArrayByTty(array $drugsArray, string $tty = 'SBD'): array
    {
        $resultArray = array_filter($drugsArray, function ($subarray) use ($tty) {
            return isset($subarray["tty"]) && $subarray["tty"] === $tty;
        });

        return $resultArray ? reset($resultArray) : [];
    }

    /**
     * Extract base names and dose form group names from drug history status.
     *
     * @param array $drugHistoryStatus The drug history status array.
     *
     * @return array An array containing base names and dose form group names extracted from the drug history status.
     */
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

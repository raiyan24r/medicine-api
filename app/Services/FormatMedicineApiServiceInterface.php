<?php
namespace App\Services;

interface FormatMedicineApiServiceInterface
{
public function findArrayByTty(array $drugsArray, string $tty = 'SBD'): array;

public function extractBaseNameAndDoseForm(array $drugHistoryStatus): array;
}

<?php
namespace App\Services;

use App\Models\User;

interface UserMedicationServiceInterface
{
public function addMedicationForUser(User $user, $rxcui): void;
public function deleteMedicationForUser(User $user, $rxcui): void;
public function getAllMedicationsForUser(User $user): array;
}

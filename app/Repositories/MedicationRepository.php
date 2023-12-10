<?php

namespace App\Repositories;

use App\Models\Medication;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MedicationRepository
{
    public function addMedication(int $userId, array $medication)
    {
        Medication::updateOrCreate(
            [
                'user_id' => $userId,
                'rxcui' => $medication['rxcui'],
            ],
            [
                'drug_name' => $medication['name'],
                'base_names' => $medication['base_names'],
                'dosage_forms' => $medication['dosage_forms']
            ]
        );
    }

    public function deleteMedication(int $userId, string $rxcui): void
    {
        Medication::where([
            'user_id' => $userId,
            'rxcui' => $rxcui,
        ])->delete();
    }

    public function getAllMedications(User $user): array
    {
        return $user->medications()->get()->toArray();
    }

}

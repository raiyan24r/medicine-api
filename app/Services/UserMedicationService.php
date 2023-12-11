<?php

namespace App\Services;

use App\Clients\DrugClient;
use App\Exceptions\NoRecordsException;
use App\Exceptions\RxcuiInvalidException;
use App\Models\User;
use App\Repositories\MedicationRepository;

class UserMedicationService implements UserMedicationServiceInterface
{
    public function __construct(
        private readonly DrugClient $drugClient,
        private readonly MedicationRepository $medicationRepository,
        private readonly FormatMedicineApiServiceInterface $formatMedicineApiService)
    {
    }

    /**
     * Add medication for a specific user based on the provided rxcui.
     *
     * @param User $user The user for whom the medication is being added.
     * @param mixed $rxcui The rxcui (RxNorm Concept Unique Identifier) of the medication.
     *
     * @throws RxcuiInvalidException If the provided rxcui is not active.
     *
     * @return void
     */
    public function addMedicationForUser(User $user, $rxcui): void
    {
        $drugHistoryStatus = $this->drugClient->historyStatusByRxcui($rxcui);

        if ($drugHistoryStatus['rxcuiStatusHistory']['metaData']['status'] != 'Active')
        {
            throw new RxcuiInvalidException('Invalid rxcui');
        }


        list($baseNames, $doseFormGroupNames) = $this->formatMedicineApiService->extractBaseNameAndDoseForm($drugHistoryStatus);

        $drugDetails = [
            'rxcui' => $rxcui,
            'name' => $drugHistoryStatus['rxcuiStatusHistory']['attributes']['name'],
            'base_names' => $baseNames,
            'dosage_forms' => $doseFormGroupNames
        ];

        $this->medicationRepository->addMedication($user->id, $drugDetails);
    }

    /**
     * Delete medication for a specific user based on the provided rxcui.
     *
     * @param User $user The user for whom the medication is being deleted.
     * @param mixed $rxcui The rxcui (RxNorm Concept Unique Identifier) of the medication.
     *
     * @throws RxcuiInvalidException If the provided rxcui is not active.
     *
     * @return void
     */
    public function deleteMedicationForUser(User $user, $rxcui): void
    {
        $drugHistoryStatus = $this->drugClient->historyStatusByRxcui($rxcui);

        if ($drugHistoryStatus['rxcuiStatusHistory']['metaData']['status'] != 'Active')
        {
            throw new RxcuiInvalidException('Invalid rxcui');
        }

        $this->medicationRepository->deleteMedication($user->id, $rxcui);
    }

    /**
     * Get all medication records for a specific user.
     *
     * @param User $user The user for whom the medication records are being retrieved.
     *
     * @throws NoRecordsException If no medication records are found for the user.
     *
     * @return array An array containing medication records for the user.
     */
    public function getAllMedicationsForUser(User $user): array
    {
        $medicationRecords = $this->medicationRepository->getAllMedications($user);

        if (empty($medicationRecords)) {
            throw new NoRecordsException();
        }
        return $medicationRecords;
    }
}

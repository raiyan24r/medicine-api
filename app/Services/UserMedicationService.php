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
     * @throws RxcuiInvalidException
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
     * @throws RxcuiInvalidException
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

    public function getAllMedicationsForUser(User $user): array
    {
        $medicationRecords = $this->medicationRepository->getAllMedications($user);

        if (empty($medicationRecords)) {
            throw new NoRecordsException();
        }
        return $medicationRecords;
    }
}

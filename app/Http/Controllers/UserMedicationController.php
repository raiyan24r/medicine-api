<?php

namespace App\Http\Controllers;

use App\Exceptions\NoRecordsException;
use App\Exceptions\RxcuiInvalidException;
use App\Http\Requests\AddDrugRequest;
use App\Http\Requests\DeleteDrugRequest;
use App\Services\UserMedicationServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Http\Helpers\httpResponse;

class UserMedicationController extends Controller
{
    public function __construct(private readonly UserMedicationServiceInterface $userMedicationService)
    {
    }

    /**
     * Add a medication record for the user based on the provided RXCUI.
     *
     * @param AddDrugRequest $request The request containing the RXCUI.
     *
     * @return JsonResponse The JSON response indicating the result of the operation.
     */
    public function addDrug(AddDrugRequest $request): JsonResponse
    {
        try{
            $rxcui = $request->get('rxcui');
            $this->userMedicationService->addMedicationForUser($request->user(), $rxcui);
            return httpResponse(201, 'Medication record added for user');
        } catch (RxcuiInvalidException $exception) {
            return httpResponse(404, 'RXCUI entered is invalid');
        } catch (Exception $e) {
            return httpResponse(400, 'Failed to add medication record');
        }
    }

    /**
     * Delete a medication record for the user based on the provided RXCUI.
     *
     * @param DeleteDrugRequest $request The request containing the RXCUI as a route parameter.
     *
     * @return JsonResponse The JSON response indicating the result of the deletion operation.
     */
    public function deleteDrug(DeleteDrugRequest $request): JsonResponse
    {
        try{
            $rxcui = $request->route('rxcui');
            $this->userMedicationService->deleteMedicationForUser($request->user(), $rxcui);
            return httpResponse(200, 'Medication record deleted for user');
        } catch (RxcuiInvalidException $exception) {
            return httpResponse(404, 'RXCUI entered is invalid');
        } catch (Exception $e) {
            return httpResponse(400, 'Failed to delete medication record');
        }
    }

    /**
     * Get all medication records for the user.
     *
     * @param Request $request The request object.
     *
     * @return JsonResponse The JSON response containing medication records for the user, if available.
     */
    public function getDrugs(Request $request): JsonResponse
    {
        try{
            $medications = $this->userMedicationService->getAllMedicationsForUser($request->user());
            return httpResponse(200, 'All medication records for the user fetched', $medications);
        } catch (NoRecordsException $exception) {
            return httpResponse(200, 'No medication record available for the user');
        } catch (Exception $e) {
            return httpResponse(400, 'Failed to fetch medication records for the user');
        }
    }
}

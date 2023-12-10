<?php

namespace App\Http\Controllers;

use App\Exceptions\RxcuiInvalidException;
use App\Http\Requests\AddDrugRequest;
use App\Http\Requests\DeleteDrugRequest;
use App\Services\UserMedicationService;
use App\Services\UserMedicationServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\Http\Helpers\httpResponse;

class UserMedicationController extends Controller
{
    public function __construct(private readonly UserMedicationServiceInterface $userMedicationService)
    {
    }

    public function addDrug(AddDrugRequest $request)
    {
        try{
            $rxcui = $request->get('rxcui');
            $this->userMedicationService->addMedicationForUser($request->user(), $rxcui);
            return httpResponse(201, 'Medication record added for user');
        } catch (RxcuiInvalidException $exception) {
            return httpResponse(404, 'RXCUI entered is invalid');
        } catch (Exception $e) {
            dd($e);
            return httpResponse(400, 'Failed to add medication record');
        }
    }

    public function deleteDrug(DeleteDrugRequest $request)
    {
        try{
            $rxcui = $request->route('rxcui');
            $this->userMedicationService->deleteMedicationForUser($request->user(), $rxcui);
            return httpResponse(200, 'Medication record deleted for user');
        } catch (RxcuiInvalidException $exception) {
            return httpResponse(404, 'RXCUI entered is invalid');
        } catch (Exception $e) {
            dd($e);
            return httpResponse(400, 'Failed to add medication record');
        }
    }
}

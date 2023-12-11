<?php

namespace Tests\Feature;

use App\Exceptions\NoRecordsException;
use App\Models\Medication;
use App\Services\UserMedicationService;
use App\Clients\DrugClient;
use App\Repositories\MedicationRepository;
use App\Services\FormatMedicineApiServiceInterface;
use App\Exceptions\RxcuiInvalidException;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMedicationServiceTest extends TestCase
{
    use RefreshDatabase;

    private $drugClientMock;
    private $medicationRepositoryMock;
    private $formatMedicineApiServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->drugClientMock = $this->createMock(DrugClient::class);
        $this->medicationRepositoryMock = new MedicationRepository();
        $this->formatMedicineApiServiceMock = $this->createMock(FormatMedicineApiServiceInterface::class);
    }

    public function testAddMedicationForUser()
    {
        $user = User::factory()->create();
        $rxcui = '123';

        $drugClientResponse = [
            'rxcuiStatusHistory' => [
                'metaData' => [
                    'status' => 'Active',
                ],
                'attributes' => [
                    'name' => 'Medication Name',
                ],
            ],
        ];

        $this->drugClientMock->expects($this->once())
            ->method('historyStatusByRxcui')
            ->with($rxcui)
            ->willReturn($drugClientResponse);

        $this->formatMedicineApiServiceMock->expects($this->once())
            ->method('extractBaseNameAndDoseForm')
            ->with($drugClientResponse)
            ->willReturn([['BaseName1'], ['DoseForm1']]);


        $userMedicationService = new UserMedicationService(
            $this->drugClientMock,
            $this->medicationRepositoryMock,
            $this->formatMedicineApiServiceMock
        );

        $userMedicationService->addMedicationForUser($user, $rxcui);

        $this->assertDatabaseHas('medications', [
            'user_id' => $user->id,
            'rxcui' => $rxcui,
            'drug_name' => 'Medication Name',
        ]);
    }


    public function testDeleteMedicationForUser()
    {

        $user = User::factory()->create();
        $rxcui = '123';

        $this->medicationRepositoryMock->addMedication($user->id, [
            'rxcui' => $rxcui,
            'name' => 'test',
            'base_names' => ['test'],
            'dosage_forms' => ['test'],
        ]);

        $this->assertDatabaseHas('medications', [
            'user_id' => $user->id,
            'rxcui' => $rxcui,
        ]);

        $drugClientResponse = [
            'rxcuiStatusHistory' => [
                'metaData' => [
                    'status' => 'Active',
                ],
            ],
        ];

        $this->drugClientMock->expects($this->once())
            ->method('historyStatusByRxcui')
            ->with($rxcui)
            ->willReturn($drugClientResponse);

        $userMedicationService = new UserMedicationService(
            $this->drugClientMock,
            $this->medicationRepositoryMock,
            $this->formatMedicineApiServiceMock
        );

        $userMedicationService->deleteMedicationForUser($user, $rxcui);

        $this->assertSoftDeleted('medications', [
            'user_id' => $user->id,
            'rxcui' => $rxcui,
        ]);
    }


    public function testGetAllMedicationsForUser()
    {
        $user = User::factory()->create();
        $rxcui = '123';

        $this->medicationRepositoryMock->addMedication($user->id, [
            'rxcui' => $rxcui,
            'name' => 'test',
            'base_names' => ['test'],
            'dosage_forms' => ['test'],
        ]);

        $this->assertDatabaseHas('medications', [
            'user_id' => $user->id,
            'rxcui' => $rxcui,
        ]);


        $userMedicationService = new UserMedicationService(
            $this->drugClientMock,
            $this->medicationRepositoryMock,
            $this->formatMedicineApiServiceMock
        );

        $result = $userMedicationService->getAllMedicationsForUser($user);

        $medicationRepositoryResponse = [
            [
                'id' => 1,
                'rxcui' => '123',
                'drug_name' => 'test',
                'base_names' => ['test'],
                'dosage_forms' => ['test'],
            ],
        ];

        $this->assertEquals($medicationRepositoryResponse, $result);
    }

    public function testGetAllMedicationsForUserThrowsNoRecordsException()
    {
        $user = User::factory()->create();
        $rxcui = '123';

        $this->assertDatabaseMissing('medications', [
            'user_id' => $user->id,
            'rxcui' => $rxcui,
        ]);

        $userMedicationService = new UserMedicationService(
            $this->drugClientMock,
            $this->medicationRepositoryMock,
            $this->formatMedicineApiServiceMock
        );

        $this->expectException(NoRecordsException::class);
        $result = $userMedicationService->getAllMedicationsForUser($user);
    }
}

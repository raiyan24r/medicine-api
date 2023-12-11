<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use App\Services\DrugSearchService;
use App\Clients\DrugClient;
use App\Services\FormatMedicineApiServiceInterface;

class DrugSearchServiceTest extends TestCase
{
    public function testDrugDetails()
    {
        $drugClient = $this->createMock(DrugClient::class);
        $formatMedicineApiService = $this->createMock(FormatMedicineApiServiceInterface::class);

        $searchByNameResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/searchByNameResponse.json'), true);
        $historyStatusResponse = json_decode(file_get_contents(__DIR__ . '/fixtures/historyStatusResponse.json'), true);

        $drugClient->expects($this->once())
            ->method('searchByName')
            ->with('azithromycin')
            ->willReturn($searchByNameResponse);

        $drugClient->expects($this->once())
            ->method('historyStatusByRxcui')
            ->with('105260')
            ->willReturn($historyStatusResponse);

        $formatMedicineApiService->expects($this->once())
            ->method('findArrayByTty')
            ->willReturn(['conceptProperties' => [['rxcui' => '105260', 'name' => 'azithromycin 40 MG/ML Oral Suspension [Zithromax]']]]);

        $formatMedicineApiService->expects($this->once())
            ->method('extractBaseNameAndDoseForm')
            ->willReturn([['azithromycin'], ['Oral Product', 'Oral Liquid Product']]);

        $drugSearchService = new DrugSearchService($drugClient, $formatMedicineApiService);

        $result = $drugSearchService->drugDetails('azithromycin');

        $expectedResult = [
            ['rxcui' => '105260', 'name' => 'azithromycin 40 MG/ML Oral Suspension [Zithromax]', 'baseNames' => ['azithromycin'], 'doseFormGroupNames' => ['Oral Product', 'Oral Liquid Product']],
        ];

        $this->assertEquals($expectedResult, $result);
    }
}

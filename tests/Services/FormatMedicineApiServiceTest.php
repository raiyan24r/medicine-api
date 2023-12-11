<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use App\Services\FormatMedicineApiService;

class FormatMedicineApiServiceTest extends TestCase
{
    public function testFindArrayByTty()
    {
        $drugsArray = [
            [
                "tty" => "BPCK"
            ],
            [
                "tty" => "SBD",
                "conceptProperties" => [
                    [
                        "rxcui" => "262095",
                        "name" => "atorvastatin 80 MG Oral Tablet [Lipitor]",
                        "synonym" => "Lipitor 80 MG Oral Tablet",
                        "tty" => "SBD",
                        "language" => "ENG",
                        "suppress" => "N",
                        "umlscui" => ""
                    ],
                    [
                        "rxcui" => "617314",
                        "name" => "atorvastatin 10 MG Oral Tablet [Lipitor]",
                        "synonym" => "Lipitor 10 MG Oral Tablet",
                        "tty" => "SBD",
                        "language" => "ENG",
                        "suppress" => "N",
                        "umlscui" => ""
                    ],
                    [
                        "rxcui" => "617318",
                        "name" => "atorvastatin 20 MG Oral Tablet [Lipitor]",
                        "synonym" => "Lipitor 20 MG Oral Tablet",
                        "tty" => "SBD",
                        "language" => "ENG",
                        "suppress" => "N",
                        "umlscui" => ""
                    ],
                    [
                        "rxcui" => "617320",
                        "name" => "atorvastatin 40 MG Oral Tablet [Lipitor]",
                        "synonym" => "Lipitor 40 MG Oral Tablet",
                        "tty" => "SBD",
                        "language" => "ENG",
                        "suppress" => "N",
                        "umlscui" => ""
                    ]
                ]
            ]
        ];

        $formatMedicineService = new FormatMedicineApiService();
        $result = $formatMedicineService->findArrayByTty($drugsArray, 'SBD');

        $this->assertCount(2, $result);
        $this->assertEquals('SBD', $result['tty']);
    }

    public function testExtractBaseNameAndDoseForm()
    {
        $drugHistoryStatus = [
            "rxcuiStatusHistory" => [
                "metaData" => [
                    "status" => "Active",
                    "source" => "RXNORM",
                    "releaseStartDate" => "042005",
                    "releaseEndDate" => "",
                    "isCurrent" => "YES",
                    "activeStartDate" => "042005",
                    "activeEndDate" => "",
                    "remappedDate" => ""
                ],
                "attributes" => [
                    "rxcui" => "105260",
                    "name" => "azithromycin 40 MG/ML Oral Suspension [Zithromax]",
                    "tty" => "SBD",
                    "isMultipleIngredient" => "NO",
                    "isBranded" => "YES"
                ],
                "definitionalFeatures" => [
                    "ingredientAndStrength" => [
                        [
                            "baseRxcui" => "18631",
                            "baseName" => "azithromycin",
                            "bossRxcui" => "18631",
                            "bossName" => "azithromycin",
                            "activeIngredientRxcui" => "18631",
                            "activeIngredientName" => "azithromycin",
                            "moietyRxcui" => "18631",
                            "moietyName" => "azithromycin",
                            "numeratorValue" => "40",
                            "numeratorUnit" => "MG",
                            "denominatorValue" => "1",
                            "denominatorUnit" => "ML"
                        ]
                    ],
                    "doseFormConcept" => [
                        [
                            "doseFormRxcui" => "316969",
                            "doseFormName" => "Oral Suspension"
                        ]
                    ],
                    "doseFormGroupConcept" => [
                        [
                            "doseFormGroupRxcui" => "1151131",
                            "doseFormGroupName" => "Oral Product"
                        ],
                        [
                            "doseFormGroupRxcui" => "1151137",
                            "doseFormGroupName" => "Oral Liquid Product"
                        ]
                    ]
                ],
                "pack" => [
                ],
                "derivedConcepts" => [
                    "ingredientConcept" => [
                        [
                            "ingredientRxcui" => "18631",
                            "ingredientName" => "azithromycin"
                        ]
                    ],
                    "scdConcept" => [
                        "scdConceptRxcui" => "141963",
                        "scdConceptName" => "azithromycin 40 MG/ML Oral Suspension"
                    ]
                ]
            ]
        ];

        $formatMedicineService = new FormatMedicineApiService();
        [$baseNames, $doseFormGroupNames] = $formatMedicineService->extractBaseNameAndDoseForm($drugHistoryStatus);

        $this->assertCount(1, $baseNames);
        $this->assertEquals(['azithromycin'], $baseNames);

        $this->assertCount(2, $doseFormGroupNames);
        $this->assertEquals(['Oral Product', 'Oral Liquid Product'], $doseFormGroupNames);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class MedicationController extends Controller
{
    public function create()
    {
        return view('medication.create-medication');
    }

    public function store(Request $request)
    {
        $accessToken = AccessToken::latest()->first();

        // Handle ingredients data
        $ingredients = [];
        $ingredientCodes = $request->input('ingredient_code');
        $ingredientDisplays = $request->input('ingredient_display');
        $ingredientStrengths = $request->input('ingredient_strength');

        // Loop through each ingredient to form the structure
        foreach ($ingredientCodes as $index => $code) {
            $ingredient = [
                "itemCodeableConcept" => [
                    "coding" => [
                        [
                            "system" => "http://sys-ids.kemkes.go.id/kfa",
                            "code" => $code,
                            "display" => $ingredientDisplays[$index]
                        ]
                    ]
                ],
                "isActive" => true,
                "strength" => [
                    "numerator" => [
                        "value" => (int) $ingredientStrengths[$index],
                        "system" => "http://unitsofmeasure.org",
                        "code" => "mg"
                    ],
                    "denominator" => [
                        "value" => 1,
                        "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                        "code" => "TAB"
                    ]
                ]
            ];
            $ingredients[] = $ingredient;
        }

        $data = [
            "resourceType" => "Medication",
            "meta" => [
                "profile" => [
                    "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                ]
            ],
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/medication/" . env('SATUSEHAT_ORGANIZATION_ID'),
                    "use" => "official",
                    "value" => $request->input('identifier_value')
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://sys-ids.kemkes.go.id/kfa",
                        "code" => $request->input('code'),
                        "display" => $request->input('display')
                    ]
                ]
            ],
            "status" => "active",
            "manufacturer" => [
                "reference" => "Organization/" . $request->input('manufacturer_reference')
            ],
            "form" => [
                "coding" => [
                    [
                        "system" => "http://terminology.kemkes.go.id/CodeSystem/medication-form",
                        "code" => $request->input('form_code'),
                        "display" => $request->input('form_display')
                    ]
                ]
            ],
            "ingredient" => $ingredients,
            "extension" => [
                [
                    "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                    "valueCodeableConcept" => [
                        "coding" => [
                            [
                                "system" => "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                "code" => $request->input('extension_code'),
                                "display" => $request->input('extension_display')
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withToken($accessToken->token)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1/Medication', $data);

            if ($response->successful()) {
                return redirect()->back()
                    ->with('success', 'Medication created successfully!')
                    ->with('request_body', json_encode($data, JSON_PRETTY_PRINT))
                    ->with('response_body', json_encode($response->json(), JSON_PRETTY_PRINT));
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to create medication: ' . $response->body())
                    ->with('request_body', json_encode($data, JSON_PRETTY_PRINT))
                    ->with('response_body', $response->body());
            }
        } catch (RequestException $e) {
            Log::error('Failed to create medication: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create medication.');
        }
    }

    public function SearchId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $medicationId = $request->input('id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Medication/' . $medicationId);

        $medication = $response->json();

        return view('medication.search-by-id', compact('medication', 'medicationId', 'accessToken', 'accessTokenExpiry'));
    }
}
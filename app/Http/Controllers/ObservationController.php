<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class ObservationController extends Controller
{
    public function SearchId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $observationId = $request->input('id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Observation/' . $observationId);

        $observation = $response->json();

        return view('observation.search-by-id', compact('observation', 'observationId', 'accessToken', 'accessTokenExpiry'));
    }
    public function SearchSubject(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $observationSubject = $request->input('subject');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Observation?subject=' . $observationSubject);

        $observation = $response->json();

        return view('observation.search-by-subject', compact('observation', 'observationSubject', 'accessToken', 'accessTokenExpiry'));
    }

    public function SearchSubjectEncounter(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $observationSubject = $request->input('subject');
        $observationEncounter = $request->input('encounter');

        if(isset($observationEncounter)){
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get(env('SATUSEHAT_FHIR_URL') . '/Observation?subject='.$observationSubject.'&encounter=' . $observationEncounter ?? null);
    
            $observation = $response->json();
        }else{
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get(env('SATUSEHAT_FHIR_URL') . '/Observation?subject='.$observationSubject.'&encounter=null');
    
            $observation = $response->json();
        }
        
        return view('observation.search-by-subject-encounter', compact('observation', 'observationSubject', 'observationEncounter', 'accessToken', 'accessTokenExpiry'));
    }
    public function SearchEncounter(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $observationEncounter = $request->input('encounter');

        // Set timeout lebih panjang, misalnya 60 detik (60000 milidetik)
        $timeout = 60000; // 60 detik

        if(isset($observationEncounter)){
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->timeout($timeout)->get(env('SATUSEHAT_FHIR_URL') . '/Observation?encounter=' . $observationEncounter);

            $observation = $response->json();
        } else {
            $null = 'null';
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->timeout($timeout)->get(env('SATUSEHAT_FHIR_URL') . '/Observation?encounter='.$null);

            $observation = $response->json();
        }

        return view('observation.search-by-encounter', compact('observation', 'observationEncounter', 'accessToken', 'accessTokenExpiry'));
    }
    public function SearchServiceRequest(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $observationSubject = $request->input('subject');
        $observationBasedOn = $request->input('based-on');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Observation?based-on='.$observationBasedOn.'&subject=' . $observationSubject);

        $observation = $response->json();

        return view('observation.search-by-service-request', compact('observation', 'observationSubject', 'observationBasedOn', 'accessToken', 'accessTokenExpiry'));
    }

    public function createObservation(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        $encounterReference = "Encounter/" . ($request->input('encounter') ?? "null");
        $value = (float) $request->input('value');

        $data = [
            "resourceType" => "Observation",
            "status" => $request->input('status'),
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => $request->input('category'),
                            "display" => $request->input('category_display')
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => $request->input('code'),
                        "display" => $request->input('display')
                    ]
                ]
            ],
            "subject" => [
                "reference" => 'Patient/'.$request->input('subject')
            ],
            "performer" => [
                [
                    "reference" => 'Practitioner/'.$request->input('performer')
                ]
            ],
            "encounter" => [
                "reference" => $encounterReference,
                "display" => $request->input('keterangan_encounter') ?? "-"
            ],
            "effectiveDateTime" => $request->input('effectiveDateTime'),
            "issued" => $request->input('issued'),
            "valueQuantity" => [
                "value" => $value,
                "unit" => "beats/minute",
                "system" => "http://unitsofmeasure.org",
                "code" => "/min"
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post(env('SATUSEHAT_FHIR_URL') . '/Observation', $data);

            if ($response->successful()) {
                return redirect()->route('observation.form')->with('success', 'Observation created successfully');
            } else {
                Log::error('Error creating observation', ['response' => $response->body()]);
                return redirect()->route('observation.form')->with('error', 'Failed to create observation:'. $response->body());
            }
        } catch (RequestException $e) {
            Log::error('Exception creating observation', ['message' => $e->getMessage()]);
            return redirect()->route('observation.form')->with('error', 'Exception occurred while creating observation');
        }
    }
    

    public function viewDetail1(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $patientName = $request->input('name');
        $patientBirth = $request->input('birthdate');
        $patientNik = $request->input('nik');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Patient?name='.$patientName.'&birthdate='.$patientBirth.'&identifier=https://fhir.kemkes.go.id/id/nik|'.$patientNik);

        $patient = $response->json();

        return view('patient.view-detail', compact('patient', 'patientName', 'patientBirth', 'patientNik', 'accessToken', 'accessTokenExpiry'));
    }

    public function createByNik()
    {
        return view('patient.create-by-nik');
    }

    public function storeByNik(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required',
            'passport' => 'required',
            'kk' => 'required',
            'name' => 'required',
            'phone_mobile' => 'required',
            'phone_home' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'birthdate' => 'required|date',
            'address_line' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'province_code' => 'required',
            'city_code' => 'required',
            'district_code' => 'required',
            'village_code' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'marital_status' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'language' => 'required',
            'birth_place_city' => 'required',
            'citizenship_status' => 'required',
        ]);

        $response = Http::post(env('SATUSEHAT_FHIR_URL') . '/Patient', [
            "resourceType" => "Patient",
            "meta" => [
                "profile" => [
                    "https://fhir.kemkes.go.id/r4/StructureDefinition/Patient"
                ]
            ],
            "identifier" => [
                [
                    "use" => "official",
                    "system" => "https://fhir.kemkes.go.id/id/nik",
                    "value" => $validatedData['nik']
                ],
                [
                    "use" => "official",
                    "system" => "https://fhir.kemkes.go.id/id/paspor",
                    "value" => $validatedData['passport']
                ],
                [
                    "use" => "official",
                    "system" => "https://fhir.kemkes.go.id/id/kk",
                    "value" => $validatedData['kk']
                ]
            ],
            "active" => true,
            "name" => [
                [
                    "use" => "official",
                    "text" => $validatedData['name']
                ]
            ],
            "telecom" => [
                [
                    "system" => "phone",
                    "value" => $validatedData['phone_mobile'],
                    "use" => "mobile"
                ],
                [
                    "system" => "phone",
                    "value" => $validatedData['phone_home'],
                    "use" => "home"
                ],
                [
                    "system" => "email",
                    "value" => $validatedData['email'],
                    "use" => "home"
                ]
            ],
            "gender" => $validatedData['gender'],
            "birthDate" => $validatedData['birthdate'],
            "deceasedBoolean" => false,
            "address" => [
                [
                    "use" => "home",
                    "line" => [
                        $validatedData['address_line']
                    ],
                    "city" => $validatedData['city'],
                    "postalCode" => $validatedData['postal_code'],
                    "country" => "ID",
                    "extension" => [
                        [
                            "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                            "extension" => [
                                [
                                    "url" => "province",
                                    "valueCode" => $validatedData['province_code']
                                ],
                                [
                                    "url" => "city",
                                    "valueCode" => $validatedData['city_code']
                                ],
                                [
                                    "url" => "district",
                                    "valueCode" => $validatedData['district_code']
                                ],
                                [
                                    "url" => "village",
                                    "valueCode" => $validatedData['village_code']
                                ],
                                [
                                    "url" => "rt",
                                    "valueCode" => $validatedData['rt']
                                ],
                                [
                                    "url" => "rw",
                                    "valueCode" => $validatedData['rw']
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "maritalStatus" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/CodeSystem/v3-MaritalStatus",
                        "code" => "M",
                        "display" => $validatedData['marital_status']
                    ]
                ],
                "text" => $validatedData['marital_status']
            ],
            "multipleBirthInteger" => 0,
            "contact" => [
                [
                    "relationship" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v2-0131",
                                    "code" => "C"
                                ]
                            ]
                        ]
                    ],
                    "name" => [
                        "use" => "official",
                        "text" => $validatedData['contact_name']
                    ],
                    "telecom" => [
                        [
                            "system" => "phone",
                            "value" => $validatedData['contact_phone'],
                            "use" => "mobile"
                        ]
                    ]
                ]
            ],
            "communication" => [
                [
                    "language" => [
                        "coding" => [
                            [
                                "system" => "urn:ietf:bcp:47",
                                "code" => "id-ID",
                                "display" => $validatedData['language']
                            ]
                        ],
                        "text" => $validatedData['language']
                    ],
                    "preferred" => true
                ]
            ],
            "extension" => [
                [
                    "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace",
                    "valueAddress" => [
                        "city" => $validatedData['birth_place_city'],
                        "country" => "ID"
                    ]
                ],
                [
                    "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus",
                    "valueCode" => $validatedData['citizenship_status']
                ]
            ]
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            // Redirect or return a success message
            return redirect()->route('patient.index')->with('success', 'Patient created successfully.');
        } else {
            // Handle error response
            $error = $response->json();
            return back()->with('error', 'Failed to create patient. Error: ' . json_encode($error));
        }
    }
}
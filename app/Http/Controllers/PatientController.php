<?php

namespace App\Http\Controllers;

use App\Models\SsPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function SearchId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $patientId = $request->input('id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Patient/' . $patientId);

        $patient = $response->json();

        return view('patient.search-by-id', compact('patient', 'patientId', 'accessToken', 'accessTokenExpiry'));
    }
    public function SearchNik(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $patientNik = $request->input('nik');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $patientNik);

        $patient = $response->json();

        return view('patient.search-by-nik', compact('patient', 'patientNik', 'accessToken', 'accessTokenExpiry'));
    }
    public function SearchBayi(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $patientNik = $request->input('nik');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Patient?identifier=https://fhir.kemkes.go.id/id/nik-ibu|' . $patientNik);

        $patient = $response->json();

        return view('patient.search-by-bayi', compact('patient', 'patientNik', 'accessToken', 'accessTokenExpiry'));
    }
    public function SearchNameBirthNik(Request $request)
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

        return view('patient.search-by-name-birth-nik', compact('patient', 'patientName', 'patientBirth', 'patientNik', 'accessToken', 'accessTokenExpiry'));
    }
    public function SearchNameBirthGender(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $patientName = $request->input('name');
        $patientBirth = $request->input('birthdate');
        $patientGender = $request->input('gender');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Patient?name='.$patientName.'&birthdate='.$patientBirth.'&gender='.$patientGender);

        $patient = $response->json();

        return view('patient.search-by-name-birth-gender', compact('patient', 'patientName', 'patientBirth', 'patientGender', 'accessToken', 'accessTokenExpiry'));
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
        return view('patient.form-add-by-nik');
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

        // Check for valid access token
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        $patientData = [
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
        ];

        try {
            Log::info('Creating patient with data: ' . json_encode($patientData));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post(env('SATUSEHAT_FHIR_URL') . '/Patient', $patientData);

            if ($response->successful()) {
                // Simpan data ke dalam database lokal
                SsPatient::create([
                    'resource_type' => $patientData['resourceType'],
                    'meta' => $patientData['meta'],
                    'identifier' => $patientData['identifier'],
                    'active' => $patientData['active'],
                    'name' => $patientData['name'],
                    'telecom' => $patientData['telecom'],
                    'gender' => $patientData['gender'],
                    'birth_date' => $patientData['birthDate'],
                    'deceased_boolean' => $patientData['deceasedBoolean'],
                    'address' => $patientData['address'],
                    'marital_status' => $patientData['maritalStatus'],
                    'multiple_birth_integer' => $patientData['multipleBirthInteger'],
                    'contact' => $patientData['contact'],
                    'communication' => $patientData['communication'],
                    'extension' => $patientData['extension'],
                ]);

                return redirect()->route('patient.index')->with('success', 'Patient created successfully.');
            } else {
                Log::error('Failed to create patient: ' . $response->body());
                $error = $response->json();
                $errorMessage = 'Failed to create patient: ' . json_encode($error, JSON_PRETTY_PRINT);
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Exception when creating patient: ' . $e->getMessage());
            $errorMessage = 'Failed to create patient due to an exception: ' . $e->getMessage();
            return back()->with('error', $errorMessage);
        }
    }

}
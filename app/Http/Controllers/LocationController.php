<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function SearchId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $locationId = $request->input('id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Location/' . $locationId);

        $location = $response->json();

        return view('location.search-by-id', compact('location', 'locationId', 'accessToken', 'accessTokenExpiry'));
    }


    public function SearchName(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $locationName = $request->input('name');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Location?name=' . $locationName);

        $location = $response->json();

        return view('location.search-by-name', compact('location', 'locationName', 'accessToken', 'accessTokenExpiry'));
    }

    public function SearchPartOf(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if($checkToken && $checkToken->expired <= now()){
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $locationOrgId = $request->input('partof');
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Location?organization=' . $locationOrgId);
    
        $location = $response->json();
    
        return view('location.search-by-partof', compact('location','locationOrgId','accessToken','accessTokenExpiry'));
    }

    public function viewLocation($id)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Location/' . $id);

        $location = $response->json();

        return view('location.view-location', compact('location'));
    }

    public function create()
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        return view('location.create-location');
    }

    public function postLocation(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        $identifierSystem = 'http://sys-ids.kemkes.go.id/location';

        try {
            $data = [
                "resourceType" => "Location",
                "identifier" => [
                    [
                        "system" => $identifierSystem.'/'.$request->input('org_id'),
                        "value" => $request->input('identifier_value')
                    ]
                ],
                "status" => $request->input('status'),
                "name" => $request->input('name'),
                "description" => $request->input('description'),
                "mode" => $request->input('mode'),
                "telecom" => [
                    [
                        "system" => "phone",
                        "value" => $request->input('telecom_phone'),
                        "use" => "work"
                    ],
                    [
                        "system" => "fax",
                        "value" => $request->input('telecom_fax'),
                        "use" => "work"
                    ],
                    [
                        "system" => "email",
                        "value" => $request->input('telecom_email')
                    ],
                    [
                        "system" => "url",
                        "value" => $request->input('telecom_url'),
                        "use" => "work"
                    ]
                ],
                "address" => [
                    "use" => "work",
                    "line" => [$request->input('address_line')],
                    "city" => $request->input('address_city'),
                    "postalCode" => $request->input('address_postalCode'),
                    "country" => $request->input('address_country'),
                    "extension" => [
                        [
                            "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                            "extension" => [
                                ["url" => "province", "valueCode" => $request->input('address_province')],
                                ["url" => "city", "valueCode" => $request->input('address_city_code')],
                                ["url" => "district", "valueCode" => $request->input('address_district')],
                                ["url" => "village", "valueCode" => $request->input('address_village')],
                                ["url" => "rt", "valueCode" => $request->input('address_rt')],
                                ["url" => "rw", "valueCode" => $request->input('address_rw')]
                            ]
                        ]
                    ]
                ],
                "physicalType" => [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/location-physical-type",
                            "code" => "ro",
                            "display" => "Room"
                        ]
                    ]
                ],
                "position" => [
                    "longitude" => floatval($request->input('position_longitude')),
                    "latitude" => floatval($request->input('position_latitude')),
                    "altitude" => floatval($request->input('position_altitude'))
                ],
                "managingOrganization" => [
                    "reference" => "Organization/" . $request->input('org_id')
                ]
            ];

            Log::info('Creating location with data: ' . json_encode($data));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post(env('SATUSEHAT_FHIR_URL') . '/Location', $data);

            if ($response->successful()) {
                return redirect()->route('location.search-by-id')->with('success', 'Location created successfully.');
            } else {
                Log::error('Failed to create location: ' . $response->body());
                return redirect()->route('location.create')->with('error', 'Failed to create location: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception when creating location: ' . $e->getMessage());
            return redirect()->route('location.create')->with('error', 'Failed to create location: ' . $e->getMessage());
        }
    }

    public function editLocation($id)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;

        // Mengambil data organisasi berdasarkan ID
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Location/' . $id);

        $location = $response->json();

        // Menampilkan form edit dengan data organisasi
        return view('location.put-location', compact('location'));
    }
    public function putLocation(Request $request, $id)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        $identifierSystem = 'http://sys-ids.kemkes.go.id/location';

        try {
            $data = [
                "resourceType" => "Location",
                "id" => $id,
                "identifier" => [
                    [
                        "system" => $identifierSystem.'/'.$request->input('org_id'),
                        "value" => $request->input('identifier_value')
                    ]
                ],
                "status" => $request->input('status'),
                "name" => $request->input('name'),
                "description" => $request->input('description'),
                "mode" => $request->input('mode'),
                "telecom" => [
                    [
                        "system" => "phone",
                        "value" => $request->input('telecom_phone'),
                        "use" => "work"
                    ],
                    [
                        "system" => "fax",
                        "value" => $request->input('telecom_fax'),
                        "use" => "work"
                    ],
                    [
                        "system" => "email",
                        "value" => $request->input('telecom_email')
                    ],
                    [
                        "system" => "url",
                        "value" => $request->input('telecom_url'),
                        "use" => "work"
                    ]
                ],
                "address" => [
                    "use" => "work",
                    "line" => [$request->input('address_line')],
                    "city" => $request->input('address_city'),
                    "postalCode" => $request->input('address_postalCode'),
                    "country" => $request->input('address_country'),
                    "extension" => [
                        [
                            "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                            "extension" => [
                                ["url" => "province", "valueCode" => $request->input('address_province')],
                                ["url" => "city", "valueCode" => $request->input('address_city_code')],
                                ["url" => "district", "valueCode" => $request->input('address_district')],
                                ["url" => "village", "valueCode" => $request->input('address_village')],
                                ["url" => "rt", "valueCode" => $request->input('address_rt')],
                                ["url" => "rw", "valueCode" => $request->input('address_rw')]
                            ]
                        ]
                    ]
                ],
                "physicalType" => [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/location-physical-type",
                            "code" => "ro",
                            "display" => "Room"
                        ]
                    ]
                ],
                "position" => [
                    "longitude" => floatval($request->input('position_longitude')),
                    "latitude" => floatval($request->input('position_latitude')),
                    "altitude" => floatval($request->input('position_altitude'))
                ],
                "managingOrganization" => [
                    "reference" => "Organization/" . $request->input('org_id')
                ]
            ];
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ])->put(env('SATUSEHAT_FHIR_URL') . '/Location/' . $id, $data);
        
                if ($response->successful()) {
                    return redirect()->route('location.search-by-id')->with('success', 'Location updated successfully.');
                } else {
                    Log::error('Failed to update location: ' . $response->body());
                    return redirect()->route('location.edit', ['id' => $id])->with('error', 'Failed to update location: ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('Exception when updating location: ' . $e->getMessage());
                return redirect()->route('location.edit', ['id' => $id])->with('error', 'Failed to update location: ' . $e->getMessage());
            }
    }
}
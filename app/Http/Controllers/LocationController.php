<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use App\Models\SsLocation; // Import model
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function SearchId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('generate-token');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $locationId = $request->input('id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Location/' . $locationId);

        $location = $response->json();

        if ($location) {
            $address = $location['address'][0] ?? [];
            $telecom = collect($location['telecom'] ?? []);

            $locationData = [
                'id' => $location['id'] ?? 'N/A',
                'name' => $location['name'] ?? 'N/A',
                'status' => $location['status'] ?? 'N/A',
                'country' => $address['country'] ?? 'N/A',
                'city' => $address['city'] ?? 'N/A',
                'address' => $address,
                'phone' => $telecom->where('system', 'phone')->first()['value'] ?? 'N/A',
                'fax' => $telecom->where('system', 'fax')->first()['value'] ?? 'N/A',
                'email' => $telecom->where('system', 'email')->first()['value'] ?? 'N/A',
                'website' => $telecom->where('system', 'url')->first()['value'] ?? 'N/A',
                'description' => $location['description'] ?? 'N/A',
                'latitude' => $location['position']['latitude'] ?? 'N/A',
                'longitude' => $location['position']['longitude'] ?? 'N/A',
                'altitude' => $location['position']['altitude'] ?? 'N/A',
            ];
        }

        $locations = SsLocation::all();

        return view('location.search-by-id', compact('location', 'locationId', 'accessToken', 'accessTokenExpiry', 'locations'));
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

        if ($location) {
            $address = $location['address'][0] ?? [];
            $telecom = collect($location['telecom'] ?? []);

            $locationData = [
                'id' => $location['id'] ?? 'N/A',
                'name' => $location['name'] ?? 'N/A',
                'status' => $location['status'] ?? 'N/A',
                'country' => $address['country'] ?? 'N/A',
                'city' => $address['city'] ?? 'N/A',
                'address' => $address,
                'phone' => $telecom->where('system', 'phone')->first()['value'] ?? 'N/A',
                'fax' => $telecom->where('system', 'fax')->first()['value'] ?? 'N/A',
                'email' => $telecom->where('system', 'email')->first()['value'] ?? 'N/A',
                'website' => $telecom->where('system', 'url')->first()['value'] ?? 'N/A',
                'description' => $location['description'] ?? 'N/A',
                'latitude' => $location['position']['latitude'] ?? 'N/A',
                'longitude' => $location['position']['longitude'] ?? 'N/A',
                'altitude' => $location['position']['altitude'] ?? 'N/A',
            ];
        }

        $locations = SsLocation::all();

        return view('location.search-by-name', compact('location', 'locationName', 'accessToken', 'accessTokenExpiry','locations'));
    }

    public function SearchPartOf(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $locationOrgId = $request->input('partof');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Location?organization=' . $locationOrgId);

        $location = $response->json();

        if ($location) {
            $address = $location['address'][0] ?? [];
            $telecom = collect($location['telecom'] ?? []);

            $locationData = [
                'id' => $location['id'] ?? 'N/A',
                'name' => $location['name'] ?? 'N/A',
                'status' => $location['status'] ?? 'N/A',
                'country' => $address['country'] ?? 'N/A',
                'city' => $address['city'] ?? 'N/A',
                'address' => $address,
                'phone' => $telecom->where('system', 'phone')->first()['value'] ?? 'N/A',
                'fax' => $telecom->where('system', 'fax')->first()['value'] ?? 'N/A',
                'email' => $telecom->where('system', 'email')->first()['value'] ?? 'N/A',
                'website' => $telecom->where('system', 'url')->first()['value'] ?? 'N/A',
                'description' => $location['description'] ?? 'N/A',
                'latitude' => $location['position']['latitude'] ?? 'N/A',
                'longitude' => $location['position']['longitude'] ?? 'N/A',
                'altitude' => $location['position']['altitude'] ?? 'N/A',
            ];
        }

        $locations = SsLocation::all();

        return view('location.search-by-partof', compact('location','locationOrgId','accessToken','accessTokenExpiry','locations'));
    }

    public function searchByOrgId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $organizationId = $request->input('organization_id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Location?organization=' . $organizationId);

        $locations = $response->json();

        return view('location.search-by-org-id', compact('locations', 'organizationId', 'accessToken', 'accessTokenExpiry'));
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

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post(env('SATUSEHAT_FHIR_URL') . '/Location', $data);

        $responseData = $response->json();

        // Log response untuk debugging dengan format JSON yang rapi
        Log::info('Response data (pretty):', ['response' => json_encode($responseData, JSON_PRETTY_PRINT)]);

        // Periksa jika 'id' ada dalam respons dan tampilkan ID jika ditemukan
        if (isset($responseData['id']) && !empty($responseData['id'])) {
            Log::info('Location ID:', ['id' => $responseData['id']]);

            // Bagian penyimpanan ke database
            SsLocation::updateOrCreate(
                ['id_location' => $responseData['id']],
                [
                    'id_location' => $responseData['id'],
                    'identifier' => $responseData['identifier'] ?? null,
                    'status' => $responseData['status'] ?? null,
                    'name' => $responseData['name'] ?? null,
                    'description' => $responseData['description'] ?? null,
                    'mode' => $responseData['mode'] ?? null,
                    'telecom' => json_encode($responseData['telecom']) ?? null,
                    'address' => json_encode([
                        'line' => $responseData['address']['line'] ?? null,
                        'city' => $responseData['address']['city'] ?? null,
                        'postalCode' => $responseData['address']['postalCode'] ?? null,
                        'country' => $responseData['address']['country'] ?? null
                    ]),
                    'physical_type' => json_encode($responseData['physicalType']) ?? null,
                    'position_longitude' => floatval($request->input('position_longitude')),
                    'position_latitude' => floatval($request->input('position_latitude')),
                    'position_altitude' => floatval($request->input('position_altitude')),
                    'managing_organization' => $responseData['managingOrganization']['reference'] ?? null,
                    'resource_type' => 'Location',
                ]
            );

            return redirect()->route('location.search-by-id')->with('success', 'Location created successfully.');
        } else {
            // Log kesalahan jika 'id' tidak ada dalam respons
            Log::error('Failed to create Location. Response does not contain ID.', ['response' => json_encode($responseData, JSON_PRETTY_PRINT)]);
            return redirect()->back()->with('error', 'Failed to create Location. Check the logs for more details.');
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

    public function putLocation(Request $request, $id_location)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        $data = [
            "resourceType" => "Location",
            "id" => $id_location,
            "identifier" => [
                [
                    "system" => 'http://sys-ids.kemkes.go.id/location/'.$request->input('org_id'),
                    "value" => $request->input('identifier_value')
                ]
            ],
            "status" => $request->input('status'),
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "mode" => "instance",
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
                    "value" => $request->input('telecom_email'),
                    "use" => "work"
                ],
                [
                    "system" => "url",
                    "value" => $request->input('telecom_url'),
                    "use" => "work"
                ]
            ],
            "address" => [
                "use" => "work",
                "line" => [
                    $request->input('address_line')
                ],
                "city" => $request->input('address_city'),
                "postalCode" => $request->input('address_postalCode'),
                "country" => $request->input('address_country'),
                "extension" => [
                    [
                        "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                        "extension" => [
                            [
                                "url" => "province",
                                "valueCode" => $request->input('address_province')
                            ],
                            [
                                "url" => "city",
                                "valueCode" => $request->input('address_city_code')
                            ],
                            [
                                "url" => "district",
                                "valueCode" => $request->input('address_district')
                            ],
                            [
                                "url" => "village",
                                "valueCode" => $request->input('address_village')
                            ],
                            [
                                "url" => "rt",
                                "valueCode" => $request->input('address_rt')
                            ],
                            [
                                "url" => "rw",
                                "valueCode" => $request->input('address_rw')
                            ]
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
        ])->put(env('SATUSEHAT_FHIR_URL') . '/Location/' . $id_location, $data);

        $responseData = $response->json();

        // Log response untuk debugging dengan format JSON yang rapi
        Log::info('Response data (pretty):', ['response' => json_encode($responseData, JSON_PRETTY_PRINT)]);

        // Periksa jika 'id' ada dalam respons dan tampilkan ID jika ditemukan
        if (isset($responseData['id']) && !empty($responseData['id'])) {
            Log::info('Location ID:', ['id' => $responseData['id']]);

            // Bagian penyimpanan ke database
            SsLocation::updateOrCreate(
                ['id_location' => $responseData['id']],
                [
                    'id_location' => $responseData['id'],
                    'identifier' => json_encode($responseData['identifier'] ?? null),
                    'status' => $responseData['status'] ?? null,
                    'name' => $responseData['name'] ?? null,
                    'description' => $responseData['description'] ?? null,
                    'mode' => $responseData['mode'] ?? null,
                    'telecom' => json_encode($responseData['telecom']) ?? null,
                    'address' => json_encode($responseData['address'] ?? null),
                    'physical_type' => json_encode($responseData['physicalType'] ?? null),
                    'position_longitude' => floatval($request->input('position_longitude')),
                    'position_latitude' => floatval($request->input('position_latitude')),
                    'position_altitude' => floatval($request->input('position_altitude')),
                    'managing_organization' => $responseData['managingOrganization']['reference'] ?? null,
                    'resource_type' => 'Location',
                ]
            );

            return redirect()->route('location.view', $responseData['id'])->with('success', 'Location updated successfully');
        } else {
            // Log kesalahan jika 'id' tidak ada dalam respons
            Log::error('Failed to create Location. Response does not contain ID.', ['response' => json_encode($responseData, JSON_PRETTY_PRINT)]);
            return redirect()->back()->with('error', 'Failed to create Location. Check the logs for more details.');
        }
    }
}
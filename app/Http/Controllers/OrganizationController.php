<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{
    public function SearchId(Request $request)
    {        
        $checkToken = AccessToken::find(1);
        if($checkToken && $checkToken->expired <= now()){
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $organizationId = $request->input('id'); // Mengambil ID dari input form

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Organization/' . $organizationId);

        $organization = $response->json();

        return view('organization.search-by-id', compact('organization','organizationId','accessToken','accessTokenExpiry'));
    }

    public function SearchName(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if($checkToken && $checkToken->expired <= now()){
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $organizationName = $request->input('name'); // Mengambil ID dari input form

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Organization?name=' . $organizationName);

        $organization = $response->json();

        return view('organization.search-by-name', compact('organization','organizationName','accessToken','accessTokenExpiry'));
    }

    public function SearchPartOf(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if($checkToken && $checkToken->expired <= now()){
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $organizationPartOf = $request->input('partof');
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Organization?partof=' . $organizationPartOf);
    
        $organization = $response->json();
    
        return view('organization.search-by-partof', compact('organization','organizationPartOf','accessToken','accessTokenExpiry'));
    }

    public function createOrganization(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        $identifierSystem = 'http://sys-ids.kemkes.go.id/organization';

        try {
            $data = [
                "resourceType" => "Organization",
                "active" => true,
                "identifier" => [
                    [
                        "use" => "official",
                        "system" => $identifierSystem,
                        "value" => $request->input('identifier_value')
                    ]
                ],
                "type" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/organization-type",
                                "code" => "dept",
                                "display" => "Hospital Department"
                            ]
                        ]
                    ]
                ],
                "name" => $request->input('name'),
                "telecom" => [
                    [
                        "system" => "phone",
                        "value" => $request->input('phone'),
                        "use" => "work"
                    ],
                    [
                        "system" => "email",
                        "value" => $request->input('email'),
                        "use" => "work"
                    ],
                    [
                        "system" => "url",
                        "value" => $request->input('url'),
                        "use" => "work"
                    ]
                ],
                "address" => [
                    [
                        "use" => "work",
                        "type" => "both",
                        "line" => [$request->input('line')],
                        "city" => $request->input('city'),
                        "postalCode" => $request->input('postalCode'),
                        "country" => "ID",
                        "extension" => [
                            [
                                "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                                "extension" => [
                                    [
                                        "url" => "province",
                                        "valueCode" => $request->input('province')
                                    ],
                                    [
                                        "url" => "city",
                                        "valueCode" => $request->input('city_code')
                                    ],
                                    [
                                        "url" => "district",
                                        "valueCode" => $request->input('district')
                                    ],
                                    [
                                        "url" => "village",
                                        "valueCode" => $request->input('village')
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                "partOf" => [
                    "reference" => "Organization/" . $request->input('partOf')
                ]
            ];

            Log::info('Creating organization with data: ' . json_encode($data));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post(env('SATUSEHAT_FHIR_URL') . '/Organization', $data);

            if ($response->successful()) {
                return redirect()->route('organization.form')->with('success', 'Organization created successfully.');
            } else {
                Log::error('Failed to create organization: ' . $response->body());
                return redirect()->route('organization.form')->with('error', 'Failed to create organization: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception when creating organization: ' . $e->getMessage());
            return redirect()->route('organization.form')->with('error', 'Failed to create organization: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        $checkToken = AccessToken::find(1);
        if($checkToken && $checkToken->expired <= now()){
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Organization/' . $id);

        $organization = $response->json();

        return view('organization.view-organization', compact('organization'));
    }

    public function editOrganization($id)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;

        // Mengambil data organisasi berdasarkan ID
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Organization/' . $id);

        $organization = $response->json();

        // Menampilkan form edit dengan data organisasi
        return view('organization.form-edit-organization', compact('organization'));
    }

    public function updateOrganization(Request $request, $id)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        try {
            $data = [
                "resourceType" => "Organization",
                "id" => $id,
                "active" => true,
                "identifier" => [
                    [
                        "use" => "official",
                        "system" => $request->input('identifier_system'),
                        "value" => $request->input('identifier_value')
                    ]
                ],
                "type" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/organization-type",
                                "code" => "dept",
                                "display" => "Hospital Department"
                            ]
                        ]
                    ]
                ],
                "name" => $request->input('name'),
                "telecom" => [
                    [
                        "system" => "phone",
                        "value" => $request->input('phone'),
                        "use" => "work"
                    ],
                    [
                        "system" => "email",
                        "value" => $request->input('email'),
                        "use" => "work"
                    ],
                    [
                        "system" => "url",
                        "value" => $request->input('url'),
                        "use" => "work"
                    ]
                ],
                "address" => [
                    [
                        "use" => "work",
                        "type" => "both",
                        "line" => [$request->input('line')],
                        "city" => $request->input('city'),
                        "postalCode" => $request->input('postalCode'),
                        "country" => "ID",
                        "extension" => [
                            [
                                "url" => "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                                "extension" => [
                                    [
                                        "url" => "province",
                                        "valueCode" => $request->input('province')
                                    ],
                                    [
                                        "url" => "city",
                                        "valueCode" => $request->input('city_code')
                                    ],
                                    [
                                        "url" => "district",
                                        "valueCode" => $request->input('district')
                                    ],
                                    [
                                        "url" => "village",
                                        "valueCode" => $request->input('village')
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                "partOf" => [
                    "reference" => "Organization/" . $request->input('partOf')
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->put(env('SATUSEHAT_FHIR_URL') . '/Organization/' . $id, $data);

            if ($response->successful()) {
                return redirect()->route('organization.search-by-id', ['id' => $id])->with('success', 'Organization updated successfully.');
            } else {
                Log::error('Failed to update organization: ' . $response->body());
                return redirect()->route('organization.edit', ['id' => $id])->with('error', 'Failed to update organization: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception when updating organization: ' . $e->getMessage());
            return redirect()->route('organization.edit', ['id' => $id])->with('error', 'Failed to update organization: ' . $e->getMessage());
        }
    }

}

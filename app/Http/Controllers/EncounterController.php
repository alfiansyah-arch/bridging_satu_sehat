<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class EncounterController extends Controller
{
    public function SearchId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $encounterId = $request->input('id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Encounter/' . $encounterId);

        $encounter = $response->json();

        return view('encounter.search-by-id', compact('encounter', 'encounterId', 'accessToken', 'accessTokenExpiry'));
    }

    public function SearchSubject(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $encounterSubject = $request->input('subject');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Encounter?subject=' . $encounterSubject);

        $encounter = $response->json();

        return view('encounter.search-by-subject', compact('encounter', 'encounterSubject', 'accessToken', 'accessTokenExpiry'));
    }

    public function viewEncounter($id)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Encounter/' . $id);

        $encounter = $response->json();

        return view('encounter.view-encounter', compact('encounter', 'accessToken', 'accessTokenExpiry'));
    }

    public function createEncounter()
    {
        return view('encounter.create-encounter');
    }

    public function storeEncounter(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'class_code' => 'required',
            'class_display' => 'required',
            'subject_reference' => 'required',
            'subject_display' => 'required',
            'participant_type_code' => 'required',
            'participant_type_display' => 'required',
            'individual_reference' => 'required',
            'individual_display' => 'required',
            'period_start' => 'required',
            'location_reference' => 'required',
            'location_display' => 'required',
            'statusHistory_status' => 'required',
            'statusHistory_period_start' => 'required',
            'serviceProvider_reference' => 'required',
            'identifier_system' => 'required',
            'identifier_value' => 'required',
        ]);

        // Check for valid access token
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        $encounterData = [
            "resourceType" => "Encounter",
            "status" => $request->status,
            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => $request->class_code,
                "display" => $request->class_display,
            ],
            "subject" => [
                "reference" => 'Patient/'.$request->subject_reference,
                "display" => $request->subject_display,
            ],
            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => $request->participant_type_code,
                                    "display" => $request->participant_type_display,
                                ],
                            ],
                        ],
                    ],
                    "individual" => [
                        "reference" => 'Practitioner/'.$request->individual_reference,
                        "display" => $request->individual_display,
                    ],
                ],
            ],
            "period" => [
                "start" => $request->period_start,
            ],
            "location" => [
                [
                    "location" => [
                        "reference" => 'Location/'.$request->location_reference,
                        "display" => $request->location_display,
                    ],
                ],
            ],
            "statusHistory" => [
                [
                    "status" => $request->statusHistory_status,
                    "period" => [
                        "start" => $request->statusHistory_period_start,
                    ],
                ],
            ],
            "serviceProvider" => [
                "reference" => 'Organization/'.$request->serviceProvider_reference,
            ],
            "identifier" => [
                [
                    "system" => 'http://sys-ids.kemkes.go.id/encounter/'.$request->identifier_system,
                    "value" => $request->identifier_value,
                ],
            ],
        ];

        try {
            Log::info('Creating encounter with data: ' . json_encode($encounterData));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post(env('SATUSEHAT_FHIR_URL') . '/Encounter', $encounterData);

            if ($response->successful()) {
                return redirect()->route('encounter.search-by-id')->with('success', 'Encounter created successfully!');
            } else {
                Log::error('Failed to create encounter: ' . $response->body());
                return redirect()->route('encounter.create')->with('error', 'Failed to create encounter: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception when creating encounter: ' . $e->getMessage());
            return redirect()->route('encounter.create')->with('error', 'Failed to create encounter: ' . $e->getMessage());
        }
    }

    public function editEncounter($id)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;

        // Mengambil data organisasi berdasarkan ID
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL') . '/Encounter/' . $id);

        $encounter = $response->json();

        // Menampilkan form edit dengan data organisasi
        return view('encounter.put-encounter', compact('encounter'));
    }

    public function putEncounter(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'class_code' => 'required',
            'class_display' => 'required',
            'subject_reference' => 'required',
            'subject_display' => 'required',
            'participant_type_code' => 'required',
            'participant_type_display' => 'required',
            'individual_reference' => 'required',
            'individual_display' => 'required',
            'period_start' => 'required',
            'location_reference' => 'required',
            'location_display' => 'required',
            'statusHistory_status' => 'required',
            'statusHistory_period_start' => 'required',
            'serviceProvider_reference' => 'required',
            'identifier_system' => 'required',
            'identifier_value' => 'required',
        ]);

        // Check for valid access token
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('satusehat.index');
        }

        $accessToken = AccessToken::find(1)->token;

        $encounterData = [
            "resourceType" => "Encounter",
            "status" => $request->status,
            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => $request->class_code,
                "display" => $request->class_display,
            ],
            "subject" => [
                "reference" => 'Patient/'.$request->subject_reference,
                "display" => $request->subject_display,
            ],
            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => $request->participant_type_code,
                                    "display" => $request->participant_type_display,
                                ],
                            ],
                        ],
                    ],
                    "individual" => [
                        "reference" => 'Practitioner/'.$request->individual_reference,
                        "display" => $request->individual_display,
                    ],
                ],
            ],
            "period" => [
                "start" => $request->period_start,
            ],
            "location" => [
                [
                    "location" => [
                        "reference" => 'Location/'.$request->location_reference,
                        "display" => $request->location_display,
                    ],
                ],
            ],
            "statusHistory" => [
                [
                    "status" => $request->statusHistory_status,
                    "period" => [
                        "start" => $request->statusHistory_period_start,
                    ],
                ],
            ],
            "serviceProvider" => [
                "reference" => 'Organization/'.$request->serviceProvider_reference,
            ],
            "identifier" => [
                [
                    "system" => 'http://sys-ids.kemkes.go.id/encounter/'.$request->identifier_system,
                    "value" => $request->identifier_value,
                ],
            ],
        ];

        try {
            Log::info('Updating encounter with data: ' . json_encode($encounterData));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->put(env('SATUSEHAT_FHIR_URL') . '/Encounter/' . $id, $encounterData);

            if ($response->successful()) {
                return redirect()->route('encounter.search-by-id')->with('success', 'Encounter updated successfully!');
            } else {
                Log::error('Failed to update encounter: ' . $response->body());
                return redirect()->route('encounter.edit', ['id' => $id])->with('error', 'Failed to update encounter: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception when updating encounter: ' . $e->getMessage());
            return redirect()->route('encounter.edit', ['id' => $id])->with('error', 'Failed to update encounter: ' . $e->getMessage());
        }
    }
}
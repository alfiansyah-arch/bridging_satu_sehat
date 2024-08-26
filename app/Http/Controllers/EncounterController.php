<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use App\Models\SsEncounter;
use Illuminate\Http\Client\RequestException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EncounterController extends Controller
{
    // Metode untuk mendapatkan token akses yang valid
    protected function getValidAccessToken()
    {
        $token = AccessToken::find(1);

        if (!$token || $token->expired <= now()) {
            return redirect()->route('generate-token');
        }

        return $token->token;
    }

    public function SearchId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('generate-token');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $encounterId = $request->input('id');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get(env('SATUSEHAT_FHIR_URL') . '/Encounter/' . $encounterId);

            $encounter = $response->json();
            $this->saveOrUpdateEncounter($encounter);

            return view('encounter.search-by-id', compact('encounter', 'encounterId', 'accessToken','accessTokenExpiry'));
        } catch (RequestException $e) {
            Log::error('Failed to fetch encounter by ID: ' . $e->getMessage());
            return redirect()->route('encounter.search-by-id')->with('error', 'Failed to fetch encounter.');
        }
    }

    public function SearchSubject(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if ($checkToken && $checkToken->expired <= now()) {
            return redirect()->route('generate-token');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $encounterSubject = $request->input('subject');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get(env('SATUSEHAT_FHIR_URL') . '/Encounter?subject=' . $encounterSubject);

            $encounters = $response->json();

            foreach ($encounters['entry'] as $entry) {
                $this->saveOrUpdateEncounter($entry['resource']);
            }

            return view('encounter.search-by-subject', compact('encounters', 'encounterSubject', 'accessToken','accessTokenExpiry'));
        } catch (RequestException $e) {
            Log::error('Failed to fetch encounters by subject: ' . $e->getMessage());
            return redirect()->route('encounter.search-by-subject')->with('error', 'Failed to fetch encounters.');
        }
    }

    public function viewEncounter($id)
    {
        $accessToken = $this->getValidAccessToken();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get(env('SATUSEHAT_FHIR_URL') . '/Encounter/' . $id);

            $encounter = $response->json();
            $this->saveOrUpdateEncounter($encounter);

            return view('encounter.view-encounter', compact('encounter', 'accessToken'));
        } catch (RequestException $e) {
            Log::error('Failed to fetch encounter: ' . $e->getMessage());
            return redirect()->route('encounter.view-encounter')->with('error', 'Failed to fetch encounter.');
        }
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
            'period_start' => 'required|date_format:Y-m-d\TH:i:s',
            'location_reference' => 'required',
            'location_display' => 'required',
            'statusHistory_status' => 'required',
            'statusHistory_period_start' => 'required|date_format:Y-m-d\TH:i:s',
            'serviceProvider_reference' => 'required',
            'identifier_system' => 'required',
            'identifier_value' => 'required',
        ]);

        $accessToken = $this->getValidAccessToken();

        $encounterData = $this->formatEncounterData($request, 'POST');

        try {
            Log::info('Creating encounter with data: ' . json_encode($encounterData));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post(env('SATUSEHAT_FHIR_URL') . '/Encounter', $encounterData);

            if ($response->successful()) {
                $this->saveOrUpdateEncounter($response->json());
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
        $accessToken = $this->getValidAccessToken();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get(env('SATUSEHAT_FHIR_URL') . '/Encounter/' . $id);

            $encounter = $response->json();
            $this->saveOrUpdateEncounter($encounter);

            return view('encounter.put-encounter', compact('encounter'));
        } catch (RequestException $e) {
            Log::error('Failed to fetch encounter for editing: ' . $e->getMessage());
            return redirect()->route('encounter.edit', ['id' => $id])->with('error', 'Failed to fetch encounter for editing.');
        }
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
            'period_start' => 'required|date_format:Y-m-d\TH:i:s',
            'period_end' => 'required|date_format:Y-m-d\TH:i:s',
            'location_reference' => 'required',
            'location_display' => 'required',
            'statusHistory_status' => 'required',
            'statusHistory_period_start' => 'required|date_format:Y-m-d\TH:i:s',
            'statusHistory_period_end' => 'required|date_format:Y-m-d\TH:i:s',
            'serviceProvider_reference' => 'required',
            'identifier_system' => 'required',
            'identifier_value' => 'required',
            'diagnosis_conditions' => 'array',
            'diagnosis_conditions.*.reference' => 'required',
            'diagnosis_conditions.*.display' => 'required',
            'diagnosis_conditions.*.rank' => 'required|integer',
            'discharge_disposition_code' => 'required',
            'discharge_disposition_display' => 'required',
            'discharge_disposition_text' => 'required',
        ]);

        $accessToken = $this->getValidAccessToken();

        $encounterData = $this->formatEncounterData($request, 'PUT');

        // Tambahkan diagnosis dan discharge disposition ke dalam data encounter
        $encounterData['diagnosis'] = $request->input('diagnosis_conditions', []);
        $encounterData['hospitalization'] = [
            'dischargeDisposition' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition',
                        'code' => $request->input('discharge_disposition_code'),
                        'display' => $request->input('discharge_disposition_display'),
                    ],
                ],
                'text' => $request->input('discharge_disposition_text'),
            ],
        ];

        try {
            Log::info('Updating encounter with data: ' . json_encode($encounterData));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->put(env('SATUSEHAT_FHIR_URL') . '/Encounter/' . $id, $encounterData);

            if ($response->successful()) {
                $this->saveOrUpdateEncounter($response->json());
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

    protected function formatEncounterData(Request $request, $method)
    {
        $data = [
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
                "start" => Carbon::parse($request->period_start)->format('Y-m-d H:i:s'),
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
                        "start" => Carbon::parse($request->statusHistory_period_start)->format('Y-m-d H:i:s'),
                        "end" => $method === 'PUT' ? Carbon::parse($request->statusHistory_period_end)->format('Y-m-d H:i:s') : null,
                    ],
                ],
            ],
            "serviceProvider" => [
                "reference" => 'Organization/'.$request->serviceProvider_reference,
            ],
            "identifier" => [
                [
                    "system" => $request->identifier_system,
                    "value" => $request->identifier_value,
                ],
            ],
        ];

        if ($method === 'PUT') {
            // Tambahkan periode akhir pada periode jika metode PUT
            $data['period']['end'] = Carbon::parse($request->period_end)->format('Y-m-d H:i:s');
        }

        return $data;
    }

    protected function saveOrUpdateEncounter($encounter)
    {
        $encounter = SsEncounter::updateOrCreate(
            ['id_encounter' => $encounter['id']],
            [
                'id_encounter' => $encounter['id'] ?? null,
                'status' => $encounter['status'] ?? null,
                'class_code' => $encounter['class']['code'] ?? null,
                'class_display' => $encounter['class']['display'] ?? null,
                'subject_reference' => $encounter['subject']['reference'] ?? null,
                'subject_display' => $encounter['subject']['display'] ?? null,
                'participant_type_code' => $encounter['participant'][0]['type'][0]['coding'][0]['code'] ?? null,
                'participant_type_display' => $encounter['participant'][0]['type'][0]['coding'][0]['display'] ?? null,
                'individual_reference' => $encounter['participant'][0]['individual']['reference'] ?? null,
                'individual_display' => $encounter['participant'][0]['individual']['display'] ?? null,
                'period_start' => isset($encounter['period']['start']) ? Carbon::parse($encounter['period']['start'])->format('Y-m-d H:i:s') : null,
                'period_end' => isset($encounter['period']['end']) ? Carbon::parse($encounter['period']['end'])->format('Y-m-d H:i:s') : null,
                'location_reference' => $encounter['location'][0]['location']['reference'] ?? null,
                'location_display' => $encounter['location'][0]['location']['display'] ?? null,
                'statusHistory_status' => $encounter['statusHistory'][0]['status'] ?? null,
                'statusHistory_period_start' => isset($encounter['statusHistory'][0]['period']['start']) ? Carbon::parse($encounter['statusHistory'][0]['period']['start'])->format('Y-m-d H:i:s') : null,
                'statusHistory_period_end' => isset($encounter['statusHistory'][0]['period']['end']) ? Carbon::parse($encounter['statusHistory'][0]['period']['end'])->format('Y-m-d H:i:s') : null,
                'serviceProvider_reference' => $encounter['serviceProvider']['reference'] ?? null,
                'identifier_system' => $encounter['identifier'][0]['system'] ?? null,
                'identifier_value' => $encounter['identifier'][0]['value'] ?? null,
            ]
        );
    }

}
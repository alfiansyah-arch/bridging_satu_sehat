<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;
use App\Models\AccessToken;
use Carbon\Carbon;

class PractitionerController extends Controller
{
    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.satusehat.base_uri');
        \Log::info('Using base URI: ', ['base_uri' => $this->baseUri]);
    }

    private function getAccessToken()
    {
        $accessTokenRecord = AccessToken::find(1);

        if (!$accessTokenRecord || Carbon::now()->gt(Carbon::parse($accessTokenRecord->expired))) {
            $satuSehatController = new SatuSehatController();
            $newToken = $satuSehatController->getAccessToken();
            \Log::info('Generated new access token: ', ['token' => $newToken]);
            return $newToken;
        }

        \Log::info('Using existing access token: ', ['token' => $accessTokenRecord->token]);
        return $accessTokenRecord->token;
    }

    public function search(Request $request)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Unable to retrieve access token'], 500);
        }

        $id = $request->input('id');
        $nik = $request->input('nik');
        $name = $request->input('name');
        $gender = $request->input('gender');
        $birthdate = $request->input('birthdate');

        $url = $this->baseUri . '/fhir-r4/v1/Practitioner';

        try {
            if ($id) {
                $url .= '/' . $id;
            } elseif ($nik) {
                $url .= '?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik;
            } elseif ($name && $gender && $birthdate) {
                $url .= "?name=$name&gender=$gender&birthdate=$birthdate";
            } else {
                return view('00Resources.practitioner.index', ['error' => 'Incomplete search criteria']);
            }

            $response = Http::withToken($accessToken)->get($url);
            $data = $response->json();

            if ($response->successful()) {
                return view('00Resources.practitioner.index', ['practitioner' => $data]);
            } else {
                return view('00Resources.practitioner.index', ['error' => 'Failed to fetch data from the API']);
            }
        } catch (RequestException $e) {
            return view('00Resources.practitioner.index', ['error' => 'Request failed: ' . $e->getMessage()]);
        }
    }
}

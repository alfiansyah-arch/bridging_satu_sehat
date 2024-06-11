<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;

class PractitionerController extends Controller
{
    public function SearchId(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if($checkToken && $checkToken->expired <= now()){
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $practitionerId = $request->input('id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Practitioner/' . $practitionerId);

        $practitioner = $response->json();

        return view('practitioner.search-by-id', compact('practitioner','practitionerId','accessToken','accessTokenExpiry'));
    }

    public function SearchNik(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if($checkToken && $checkToken->expired <= now()){
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $practitionerNik = $request->input('nik');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $practitionerNik);

        $practitioner = $response->json();

        return view('practitioner.search-by-nik', compact('practitioner','practitionerNik','accessToken','accessTokenExpiry'));
    }

    public function SearchName(Request $request)
    {
        $checkToken = AccessToken::find(1);
        if($checkToken && $checkToken->expired <= now()){
            return redirect()->route('satusehat.index');
        }
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $practitionerName = $request->input('name');
        $practitionerGender = $request->input('gender');
        $practitionerBirthdate = $request->input('birthdate');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Practitioner?name='.$practitionerName.'&gender='.$practitionerGender.'&birthdate=' . $practitionerBirthdate);

        $practitioner = $response->json();

        return view('practitioner.search-by-name', compact('practitioner','practitionerName','practitionerGender','practitionerBirthdate','accessToken','accessTokenExpiry'));
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
        ])->get(env('SATUSEHAT_FHIR_URL').'/Practitioner/' . $id);

        $practitioner = $response->json();

        return view('practitioner.view-practitioner', compact('practitioner'));
    }
}

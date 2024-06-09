<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;

class OrganizationController extends Controller
{
    public function SearchId(Request $request)
    {
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
        $accessToken = AccessToken::find(1)->token;
        $accessTokenExpiry = AccessToken::find(1)->expired;
        $organizationName = $request->input('name'); // Mengambil ID dari input form

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Organization?name=' . $organizationName);

        $organization = $response->json();

        return view('organization.search-by-name', compact('organization','organizationName','accessToken','accessTokenExpiry'));
    }

    public function view($id)
    {
        $accessToken = AccessToken::find(1)->token;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get(env('SATUSEHAT_FHIR_URL').'/Organization/' . $id);

        $organization = $response->json();

        return view('organization.view-organization', compact('organization'));
    }
}

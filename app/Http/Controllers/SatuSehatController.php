<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\AccessToken;
use Carbon\Carbon;

class SatuSehatController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.satusehat.base_uri'),
        ]);
    }

    // Mendapatkan akses token
    public function getAccessToken()
    {
        $client = new Client();
        try {
            $accessTokenRecord = AccessToken::find(1);
            if ($accessTokenRecord->token == NULL || $accessTokenRecord->expired == NULL || $this->isTokenExpired($accessTokenRecord->expired)) {
                $response = $client->request('POST', config('services.satusehat.base_uri') . '/oauth2/v1/accesstoken?grant_type=client_credentials', [
                    'form_params' => [
                        'client_id' => env('SATUSEHAT_CLIENT_KEY'),
                        'client_secret' => env('SATUSEHAT_CLIENT_SECRET'),
                    ],
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                ]);
    
                $data = json_decode($response->getBody()->getContents(), true);
                $kode_token = $data['access_token'];
                $expired_token = Carbon::now()->addSeconds($data['expires_in'])->format('Y-m-d H:i:s');

                if ($kode_token) {
                    AccessToken::updateOrCreate(['id' => 1], [
                        'token' => $kode_token,
                        'expired' => $expired_token,
                    ]);
                }
            } else {
                if (Carbon::now()->gte(Carbon::parse($accessTokenRecord->expired))) {
                    AccessToken::updateOrCreate(['id' => 1], [
                        'expired' => 'EXPIRED',
                    ]);
                }
                $token_record = $accessTokenRecord->token;
                $expired_record = $accessTokenRecord->expired;
            }

            return ['access_token' => $token_record, 'expiry' => $expired_record];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorMessage = $response->getBody()->getContents();
                return response()->json(['error' => "Error getting access token: $statusCode - $errorMessage"], $statusCode);
            }
            return response()->json(['error' => 'Error getting access token: Unknown error'], 500);
        }
    }

    private function isTokenExpired($expiresAt)
    {
        return Carbon::now()->gt(Carbon::parse($expiresAt));
    }

    public function index()
    {
        $successMessage = '';
        $errorMessage = '';
        $accessTokenData = null;
        $accessToken = null;
        $accessTokenExpiry = null;
        $practitioners = [];

        try {
            $accessTokenData = $this->getAccessToken();

            if ($accessTokenData) {
                $accessToken = $accessTokenData['access_token'];
                $accessTokenExpiry = $accessTokenData['expiry'];

                if ($accessToken) {
                    $successMessage = 'Access token berhasil didapatkan';
                } else {
                    $errorMessage = 'Gagal mendapatkan access token';
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return view('generate-token', compact('successMessage', 'errorMessage', 'accessToken', 'accessTokenExpiry', 'practitioners'));
    }
}

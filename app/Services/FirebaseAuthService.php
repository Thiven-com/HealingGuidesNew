<?php
// app/Services/FirebaseAuthService.php

namespace App\Services;

use App\Models\FCMToken;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FirebaseAuthService
{
    protected $serviceAccountKeyFile;

    public function __construct()
    {
        $this->serviceAccountKeyFile = public_path('serviceAccountKey.json');
        // $this->serviceAccountKeyFile = storage_path('app/serviceAccountKey.json');
    }

    public function generateAccessToken()
    {
        $credentials = json_decode(file_get_contents($this->serviceAccountKeyFile), true);

        $now_seconds = Carbon::now()->timestamp;
        $privateKey = $credentials['private_key'];
        $clientEmail = $credentials['client_email'];
        // dd($clientEmail);
        $payload = [
            'iss' => $clientEmail,
            'sub' => $clientEmail,
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now_seconds,
            'exp' => $now_seconds + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    public function fetchAccessToken()
    {

        $jwt = $this->generateAccessToken();

        $response = Http::withoutVerifying()->asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

        return $response->json();
    }

    public function accessToken()
    {
        $fcmBearer = FCMToken::first();

        if (isset($fcmBearer->id)) {

            $now = Carbon::now();
            $tokenTime = Carbon::parse($fcmBearer->now_time);

            $diff = $tokenTime->diffInMinutes($now);
            if ($diff >= 50) {
                $accessTokenData = $this->fetchAccessToken();
                if (isset($accessTokenData['access_token'])) {
                    $fcmBearer->token = $accessTokenData['access_token'];
                    $fcmBearer->date = Carbon::now()->format('Y-m-d');
                    $fcmBearer->time = Carbon::now()->format('G:i:s');
                    $fcmBearer->now_time = Carbon::now();
                    $fcmBearer->save();
                    $res['token'] = $accessTokenData['access_token'];
                    $res['success'] = 1;
                    $res['tokenTime'] = $tokenTime;
                    $res['nowTime'] = $now;
                    $res['diff'] = $diff;
                    $res['message'] = 'AccessTokenFeteched New and Stored';
                } else {
                    $res['success'] = 0;
                    $res['message'] = 'AccessToken Failed';
                    $res['tokenTime'] = $tokenTime;
                    $res['nowTime'] = $now;
                    $res['diff'] = $diff;
                }
            } else {
                $res['token'] = $fcmBearer->token;
                $res['success'] = 1;
                $res['tokenTime'] = $tokenTime;
                $res['nowTime'] = $now;
                $res['diff'] = $diff;
                $res['message'] = 'AccessToken Feteched From Table';
            }
        } else {
            $accessTokenData = $this->fetchAccessToken();
            if (isset($accessTokenData['access_token'])) {
                $data = new FCMToken();
                $data->token = $accessTokenData['access_token'];
                $data->date = Carbon::now()->format('Y-m-d');
                $data->time = Carbon::now()->format('G:i:s');
                $data->now_time = Carbon::now();
                $data->save();
                $res['token'] = $accessTokenData['access_token'];
                $res['success'] = 1;
                $res['tokenTime'] = "NA";
                $res['nowTime'] = "NA";
                $res['diff'] = "NA";
                $res['message'] = 'AccessToken Newly Generated and Stored';
            } else {
                $res['success'] = 0;
                $res['tokenTime'] = "NA";
                $res['nowTime'] = "NA";
                $res['diff'] = "NA";
                $res['message'] = 'AccessToken Newly Feteching Failed';
            }
        }

        return $res;
    }
}

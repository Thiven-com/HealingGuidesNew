<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected string $endpoint = 'https://brandbooster.app/api/0c084285-0cd8-48ba-9164-763310ab1297/contact/send-template-message';
    protected string $token = 'xd1NWK4wUdchgaAgUDvp7q6Cf93aTmmvJ9ljO5gLvSSktJ5bltcFCIwFNFapkERw';

    public function sendTemplateMessage(array $data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->endpoint}?token={$this->token}", $data);
        return $response->json();
    }
}

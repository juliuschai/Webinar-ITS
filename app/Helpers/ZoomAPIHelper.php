<?php

namespace App\Helpers;

class ZoomAPIHelper
{

    static function generate_token()
    {
        // Generate new JSON Web Token
        $token_header = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';
        $token_payload = [
            'iss' => env('ZOOM_API_KEY'),
            'exp' => (time() + 15)
        ];
        $token_payload = base64_encode(json_encode($token_payload));
        $token_payload = str_replace(['+', '/', '='], ['-', '_', ''], $token_payload);
        $token_signature = hash_hmac('sha256', "$token_header.$token_payload", env('ZOOM_API_SECRET'), true);
        $token_signature = base64_encode($token_signature);
        $token_signature = str_replace(['+', '/', '='], ['-', '_', ''], $token_signature);
        $token = "$token_header.$token_payload.$token_signature";
        return $token;
    }
}

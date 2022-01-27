<?php

class JWTCodec
{

    public function encode(array $payload): string
    {

        $header = json_encode([

            "typ" => "JWT",
            "alg" => "HS256"
        ]);
        $header = $this->base64urlEncode($header);

        $payload = json_encode(($payload));
        $payload = $this->base64urlEncode($payload);

        $signature = hash_hmac(
            "sha256",
            $header . "." . $payload,
            "7538782F413F442A472D4B6150645367566B5970337336763979244226452948",
            true
        );

        $signature = $this->base64urlEncode($signature);

        return $header . "." . $payload . "." . $signature;
    }

    private function base64urlEncode(string $text): string
    {
        return str_replace(
            ["+", "/", "="],
            ["-", "_", ""],
            base64_encode($text)
        );
    }
}

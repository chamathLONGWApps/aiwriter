<?php

namespace App\Libraries;

class OpenAi
{
    private $apiKey = $_ENV['openAiKey'];

    public function createComplition($prompt) 
    {
        $endpoint = "https://api.openai.com/v1/completions";
        $body = [
            "model" => "text-davinci-003",
            "prompt" => $prompt,
            "max_tokens" => 3000
        ];
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->apiKey}"
        ];
        return $this->doPost($endpoint, $headers, $body);
    }

    private function doPost($url, $headers = [],  $body = [], $retries = 0)
    {
        $retries += 1;
        $body = json_encode($body);
        $connection = curl_init();
        curl_setopt($connection, CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($connection, CURLOPT_POST, 1);
        curl_setopt($connection, CURLOPT_POSTFIELDS, $body);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($connection);
        $error_msg = curl_error($connection);
        $httpCode = curl_getinfo($connection, CURLINFO_HTTP_CODE);
        if ($httpCode == 200) {
            return [false, json_decode($response)];
        } else {
            if ($retries < 3)
                $this->doPost($url, $headers, $body, $retries);
            return [true, $error_msg];
        }
    }

}
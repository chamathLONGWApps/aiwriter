<?php

namespace App\Libraries;

class WriteSonic
{
    public function doPost()
    {
        $url = 'https://api.writesonic.com/v2/business/content/ai-article-writer-v3?engine=economy&language=en';
        $headers = [
            'X-API-KEY: 197731ee-2da7-46e9-ae9c-a4d0a95014af',
            'accept: application/json',
            'content-type: application/json'
        ];
        $body = [
            "article_title" => "Tree Trimming",
            "article_sections" => [
                "what is triming",
                "what it is use to"
            ],
            "article_intro" => "Write a 200 to 300 word article about tree trimming."
        ];
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
        $code = curl_getinfo($connection, CURLINFO_HTTP_CODE);;
        log_message('debug', print_r($response, true));
        log_message('debug', print_r($error_msg, true));
        log_message('debug', print_r($code, true));
    }
}

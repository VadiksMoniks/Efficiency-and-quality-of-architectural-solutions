<?php 

namespace App\Services;

class APIRequestSender{

    public function sendRequest(array $settings)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $settings);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;

    }
}
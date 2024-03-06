<?php

namespace App\Services;

class GeoAPI {

    private const FLIGHTS_API_KEY = "secret";
    private const SECRET_FLIGHTS_KEY = "secret";
    private const API_MATRIX_KEY = "secret";
    private const COUNTRY_CODE_API_KEY = "secret";

    public $access_tocken = '';


    public function getFlightOffers()
    {   
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://test.api.amadeus.com/v1/security/oauth2/token',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(array(
                'client_id' => self::FLIGHTS_API_KEY,
                'client_secret' => self::SECRET_FLIGHTS_KEY,
                'grant_type' => 'client_credentials'
            )),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')

        ]);

        $response = json_decode(($ch));
        curl_close($ch);

        $this->access_tocken = $response->access_token;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://test.api.amadeus.com/v2/shopping/flight-offers?originLocationCode=KTW&destinationLocationCode=VIE&departureDate=2024-03-10&returnDate=2024-03-17&adults=1&max=1',
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' .$this->access_tocken]
        ]);

        $response = json_decode(($ch));
        curl_close($ch);
        return $response;


    }

    public function getDistance()
    {
        $url = 'https://api.distancematrix.ai/maps/api/distancematrix/json?origins=Lviv&destinations=Kyiv&key='.self::API_MATRIX_KEY;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = json_decode(($ch));
        curl_close($ch);

        return $response; 
    }

    public function getNearestAirPort($city)
    {
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://test.api.amadeus.com/v1/security/oauth2/token',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(array(
                'client_id' => self::FLIGHTS_API_KEY,
                'client_secret' => self::SECRET_FLIGHTS_KEY,
                'grant_type' => 'client_credentials'
            )),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')

        ]);

        $response = json_decode(($ch));
        curl_close($ch);

        $this->access_tocken = $response->access_token;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.api-ninjas.com/v1/city?name=$city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("X-Api-Key: ".self::COUNTRY_CODE_API_KEY)
        ]);

        $result = json_decode(($ch));
        curl_close($ch);

        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://test.api.amadeus.com/v1/reference-data/locations/airports?latitude=".$result[0]->latitude."&longitude=".$result[0]->longitude."&radius=500&page%5Blimit%5D=10&page%5Boffset%5D=0",
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' .$this->access_tocken]
        ], true);

        $data = json_decode(($ch));
        curl_close($ch);

        $response = [
            'airports' => $data['data'],
            'countryCode' => $result[0]->country
        ];

        return $response;

    }
}
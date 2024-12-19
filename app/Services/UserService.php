<?php

namespace App\Services;

use GuzzleHttp\Client;

class UserService
{
    public function getLatLong($data)
    {
        $apiKey = config('services.locationiq.api_key');
        $address = $data['destination'];
        $encodedAddress = urlencode($address);

        $url = "https://us1.locationiq.com/v1/search?key={$apiKey}&q={$encodedAddress}&format=json";

        $client = new Client();

        try {
            $response = $client->get($url);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            $givenLatitude = $data[0]['lat'];
            $givenLongitude = $data[0]['lon'];
            $address_coordinate = $givenLatitude . ',' . $givenLongitude;
            return $address_coordinate;
        } catch (\Exception $e) {
            return response()->json(['errors' => "Error fetching data from Location IQ service."], 503);
        }
    }
}

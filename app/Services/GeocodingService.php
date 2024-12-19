<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeocodingService
{
    //for multiple address conversion use this method
    public function geocodeAddresses(array $addresses)
    {
        $apiKey = config('services.google.api_key');
        $client = new Client();
        $coordinates = [];

        foreach ($addresses as $addressData) {
            $id = $addressData['id'];
            $address = $addressData['address'];
            $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'query' => [
                    'address' => urlencode($address),
                    'key' => $apiKey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            if ($data['status'] == 'OK' && isset($data['results'][0]['geometry']['location'])) {
                $coordinates[] = [
                    'id' => $id,
                    'lat' => $data['results'][0]['geometry']['location']['lat'],
                    'lng' => $data['results'][0]['geometry']['location']['lng'],
                ];
            } else {
                return null;
            }
        }
        return $coordinates;
    }

    //for a single address conversion use this method

    public function geocodeAddress($address)
    {
        $apiKey = config('services.google.api_key');
        $client = new Client();
        $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'query' => [
                'address' => urlencode($address),
                'key' => $apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        if ($data['status'] == 'OK' && isset($data['results'][0]['geometry']['location'])) {
            return $data['results'][0];
        }
        return null;
    }
}

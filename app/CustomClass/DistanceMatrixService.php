<?php

namespace App\CustomClass;

use GuzzleHttp\Client;


class DistanceMatrixService
{
    protected $client;
    protected $apiKey;
    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.google.api_key');
    }

    public function getDistance($origin, $destination)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$this->apiKey";

        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);
        return $data;
    }
}

<?php

namespace App\ZTools\GeoUtils;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LngLatToLocation
{
    private Client $client;
    private string $key;
    private mixed $geocodeData;

    public function __construct(string $key, int $timeout = 10)
    {
        $this->client = new Client([
            "base_uri" => "https://restapi.amap.com",
            "timeout" => $timeout
        ]);
        $this->key = $key;
    }


    /**
     * @throws GuzzleException
     */
    public function lngLatToLocation(float $lng, float $lat): static
    {
        $response = $this->client->request("GET", "/v3/geocode/regeo", [
            "query" => [
                "key" => $this->key,
                "location" => $lng . "," . $lat
            ],
        ]);
        $body = $response->getBody();
        $content = $body->getContents();
        $data = json_decode($content);
        $this->geocodeData = $data;
        return $this;
    }

    /**
     * 获取详细地址
     * @return string
     */
    public function getFormatAddress(): string
    {
        return (string)$this->geocodeData->regeocode->formatted_address;
    }

    /**
     * 获取省,自治区
     * @return string
     */
    public function getProvince(): string
    {
        return (string)$this->geocodeData->regeocode->addressComponent->province;
    }

    /**
     * 获取城市
     * @return string
     */
    public function getCity(): string
    {
        return (string)$this->geocodeData->regeocode->addressComponent->city;
    }

    /**
     * 获取城市编码
     * @return string
     */
    public function getAdCode(): string
    {
        return (string)$this->geocodeData->regeocode->addressComponent->adcode;
    }

    /**
     * 获取管辖区
     * @return string
     */
    public function getDistrict(): string
    {
        return (string)$this->geocodeData->regeocode->addressComponent->district;
    }

}

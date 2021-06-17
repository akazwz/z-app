<?php


namespace App\ZTools\GeoUtils;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use function GuzzleHttp\Psr7\str;

class IPToLocation
{
    private Client $client;
    private string $key;
    private mixed $locationData;

    public function __construct(string $key, int $timeout = 10)
    {
        $this->client = new Client([
            "base_uri" => "https://restapi.amap.com",
            "timeout" => $timeout
        ]);
        $this->key = $key;
    }

    /**
     * 获取ip地址位置
     * @throws GuzzleException
     */
    public function iPToLocation(string $ip): static
    {
        $response = $this->client->request("GET", "/v3/ip", [
            "query" => [
                "key" => $this->key,
                "ip" => $ip,
            ],
        ]);
        $body = $response->getBody();
        $content = $body->getContents();
        $data = json_decode($content);
        $this->locationData = $data;
        return $this;
    }

    /**
     * 获取省份
     * @return string
     */
    public function getProvince(): string
    {
        return (string)$this->locationData->province;
    }

    /**
     * 获取城市
     * @return string
     */
    public function getCity(): string
    {
        return (string)$this->locationData->city;
    }

    /**
     * 获取城市编码
     * @return string
     */
    public function getAdCode(): string
    {
        return (string)$this->locationData->adcode;
    }

    /**
     * 获取ip地址范围坐标
     * @return string
     */
    public function getRectangle(): string
    {
        return (string)$this->locationData->rectangle;
    }


}

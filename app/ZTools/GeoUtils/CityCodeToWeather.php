<?php


namespace App\ZTools\GeoUtils;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CityCodeToWeather
{
    private Client $client;
    private string $key;
    private mixed $weatherData;

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
    public function cityCodeToWeather(string $adCode): static
    {
        $response = $this->client->request("GET", "/v3/weather/weatherInfo", [
            "query" => [
                "key" => $this->key,
                "city" => $adCode
            ],
        ]);
        $body = $response->getBody();
        $content = $body->getContents();
        $data = json_decode($content);
        $this->weatherData = $data;
        return $this;
    }

    /**获取天气的所有数据
     * @return string
     */
    public function getWeatherData(): string
    {
        return (string)$this->weatherData->lives[0];
    }

    /**获取省份
     * @return string
     */
    public function getProvince(): string
    {
        return (string)$this->weatherData->lives[0]->province;
    }

    /**
     * 获取城市
     * @return string
     */
    public function getCity(): string
    {
        return (string)$this->weatherData->lives[0]->city;
    }

    /**
     * 获取城市编码
     * @return string
     */
    public function getAdCode(): string
    {
        return (string)$this->weatherData->lives[0]->adcode;
    }

    /**
     * 获取天气
     * @return string
     */
    public function getWeather(): string
    {
        return (string)$this->weatherData->lives[0]->weather;
    }

    /**
     * 获取温度
     * @return string
     */
    public function getTemperature(): string
    {
        return (string)$this->weatherData->lives[0]->temperature;
    }

    /**
     * 获取风向
     * @return string
     */
    public function getWindDirection(): string
    {
        return (string)$this->weatherData->lives[0]->winddirection;
    }

    /**
     * 获取风力
     * @return string
     */
    public function getWindPower(): string
    {
        return (string)$this->weatherData->lives[0]->windpower;
    }

    /**
     * 获取湿度
     * @return string
     */
    public function getHumidity(): string
    {
        return (string)$this->weatherData->lives[0]->humidity;
    }

    /**
     * 获取更新时间
     * @return string
     */
    public function getReportTime(): string
    {
        return (string)$this->weatherData->lives[0]->reporttime;
    }
}

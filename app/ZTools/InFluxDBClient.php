<?php


namespace App\ZTools;


use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\QueryApi;
use InfluxDB2\WriteApi;

class InFluxDBClient
{
    private Client $client;
    private string $writePrecision;
    private WriteApi $writeApi;
    private QueryApi $queryApi;

    public function __construct($url, $token, $org = 'z-app', $debug = false)
    {
        $this->client = new Client([
            'url' => $url,
            'token' => $token,
            'org' => 'icegps',
            'debug' => $debug,
        ]);

        $this->writePrecision = WritePrecision::S;
        $this->writeApi = $this->client->createWriteApi();
        $this->queryApi = $this->client->createQueryApi();
    }

    public function write($bucket, $data, $precision = null)
    {
        if (!isset($precision)) {
            $precision = $this->writePrecision;
        }
        $this->writeApi->write($data, $precision, $bucket);
    }

    public function query($queryStr): array
    {
        return $this->queryApi->query($queryStr);
    }


}

<?php

namespace App\Http\Controllers;

use App\ZTools\InFluxDBClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfluxDBController extends Controller
{
    private InFluxDBClient $inFluxDB;

    public function __construct()
    {
        $url = env('INFLUXDB_URL');
        $token = env('INFLUXDB_TOKEN');
        $this->inFluxDB = new InFluxDBClient($url, $token);
    }

    public function queryData(): JsonResponse
    {
        return response()->json('');
    }

    public function getWorkTime(Request $request): JsonResponse
    {
        $sn = $request->get('sn', '1140201213940');
        $startDateStr = $request->get('start', '2021-04-01');
        $stopDateStr = $request->get('stop', '2021-05-01');
        $start = gmdate(DATE_ATOM, strtotime($startDateStr));
        $stop = gmdate(DATE_ATOM, strtotime($stopDateStr));

        $workData = $this->getWorkTimeData($sn, $start, $stop);
        $workHoursData = [];
        foreach ($workData as $data) {
            $workDateStr = $data->records[0]->values['_start'];
            $workDate = date('Y-m-d', strtotime($workDateStr));
            $workCount = $data->records[0]->values['_value'];
            $workHours = round($workCount / 12 / 60, 2);
            array_push($workHoursData, [$workDate => $workHours]);
        }
        return response()->json($workHoursData);
    }

    /**
     * @param $sn
     * @param $start
     * @param $stop
     * @param null $auto
     * @param string $interval
     * @return array
     */
    public function getWorkTimeData($sn, $start, $stop, $auto = null, string $interval = '24h'): array
    {
        if (!isset($auto)) {
            $auto = '0 or r["_value"] == 1';
        }
        $queryStr = 'from(bucket: "track")
            |> range(start: ' . $start . ', stop: ' . $stop . ')
            |> filter(fn: (r) => r["_measurement"] == "motion")
            |> filter(fn: (r) => r["_field"] == "auto")
            |> filter(fn: (r) => r["sn"] == "' . $sn . '")
            |> filter(fn: (r) => r["_value"] ==' . $auto . ')
            |> window(every: ' . $interval . ')
            |> count()';
        return $this->inFluxDB->query($queryStr);
    }
}

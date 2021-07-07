<?php

namespace App\Http\Controllers;

use App\ZTools\InFluxDBClient;
use GeometryLibrary\SphericalUtil;
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

    public function getWorkDataToShow(Request $request): JsonResponse
    {
        $sn = '1140201213520';
        $sns = [$sn, '120032033032'];
        $from = $request->get('from', '2021-6-30');
        $end = $request->get('to', '2021-7-7');
        $type = $request->get('type', 'all');
        if ($type != 'all') {
            $sns = ['1140201213520'];
        }

        $auto = [];
        $grader = [];
        $all = [];
        foreach ($sns as $sn) {
            $timeDistanceArea = $this->getTimeDistanceArea($sn, $from, $end);
            $type = 0;
            switch ($type) {
                case 0:
                    array_push($auto, $timeDistanceArea);
                    break;
                case 1:
                    array_push($grader, $timeDistanceArea);
                    break;
            }
        }


        return response()->json($auto);
    }

    public function getTimeDistanceArea($sn, $from, $end): array
    {
        $endDateRFC3339 = gmdate(DATE_RFC3339, strtotime($end));
        $i = 0;
        $res = [];
        // 按照日期进行循环计算数据, 当日期循环到结束日期时,循环结束
        while (true) {
            $fromTime = strtotime('+' . $i . 'day', strtotime($from));
            $toTime = strtotime('+' . $i + 1 . 'day', strtotime($from));
            $fromDateRFC3339 = gmdate(DATE_RFC3339, $fromTime);
            $fromDateYmd = date('Y-m-d', $fromTime);
            $toDateRFC3339 = gmdate(DATE_RFC3339, $toTime);
            $originInfluxTime = $this->getWorkTimeHours($sn, $fromDateRFC3339, $toDateRFC3339);
            if (!empty($originInfluxTime)) {
                $counts = $originInfluxTime[0]->records[0]->values['_value'];
                $time = round($counts / 12 / 60, 2);
                $originInfluxLngLat = $this->getWorkLngLatData($sn, $fromDateRFC3339, $toDateRFC3339);
                if (!empty($originInfluxLngLat)) {
                    $path = [];
                    $lngLatTables = $originInfluxLngLat[0]->records;
                    foreach ($lngLatTables as $lngLatTable) {
                        $lat = $lngLatTable->values['lat'];
                        $lng = $lngLatTable->values['lon'];
                        array_push($path, [
                            'lat' => $lat,
                            'lng' => $lng,
                        ]);
                    }
                    $distanceAndArea = $this->getDistanceAreaByPath($path);
                    array_push($res, [
                        'day' => $fromDateYmd,
                        'time' => $time,
                        'distance' => $distanceAndArea['distance'],
                        'area' => $distanceAndArea['area'],
                    ]);
                }
            }
            if ($fromDateRFC3339 == $endDateRFC3339) {
                break;
            }
            $i++;
        }
        return $res;
    }

    public function getWorkLngLatData($sn, $start, $stop, $auto = null): array
    {
        if (!isset($auto)) {
            $auto = '0 or r["_value"] == 1';
        }
        $queryStr = 'import "influxdata/influxdb/schema"
            from(bucket: "track")
            |> range(start: ' . $start . ', stop: ' . $stop . ')
            |> filter(fn: (r) => r["_measurement"] == "motion")
            |> filter(fn: (r) => r["taskType"] == "0")
            |> filter(fn: (r) => r["sn"] == "' . $sn . '")
            |> schema.fieldsAsCols()
            |> filter(fn: (r) => r["rtkStatus"] == 4)
            |> filter(fn: (r) => r["auto"] == ' . $auto . ')
            |> keep(columns: ["lon", "lat", "_time"])';
        return $this->inFluxDB->query($queryStr);
    }

    public function getWorkTimeHours($sn, $start, $stop, $auto = null): array
    {
        if (!isset($auto)) {
            $auto = '0 or r["_value"] == 1';
        }
        $queryStr = 'from(bucket: "track")
            |> range(start: ' . $start . ', stop: ' . $stop . ')
            |> filter(fn: (r) => r["_measurement"] == "motion")
            |> filter(fn: (r) => r["taskType"] == "0")
            |> filter(fn: (r) => r["sn"] == "' . $sn . '")
            |> filter(fn: (r) => r["_field"] == "auto")
            |> filter(fn: (r) => r["_value"] ==' . $auto . ')
            |> count()';
        return $this->inFluxDB->query($queryStr);
    }

    public function getDistanceAreaByPath($path): array
    {
        $distance = SphericalUtil::computeLength($path);
        $area = SphericalUtil::computeArea($path);
        $area = (float)$area * 0.0015;
        return [
            'distance' => round($distance, 2),
            'area' => round($area, 2)
        ];
    }
}

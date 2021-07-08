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

        $autoContainer = [];
        $autoSumTda = [
            'time' => 0,
            'distance' => 0,
            'area' => 0,
        ];
        $autoChartDataSet = [];
        if (!empty($auto)) {
            $autoContainer = $this->mergeSNsTypeTda($auto, $autoContainer);
            $autoSumTda = $this->getSumTda($autoContainer);
            $autoChartDataSet = $this->getChartDataSet($autoContainer);
        }
        $graderContainer = [];
        $graderSumTda = [
            'time' => 0,
            'distance' => 0,
            'area' => 0,
        ];
        $graderChartDataSet = [];
        if (!empty($grader)) {
            $graderContainer = $this->mergeSNsTypeTda($grader, $graderContainer);
            $graderSumTda = $this->getSumTda($graderContainer);
            $graderChartDataSet = $this->getChartDataSet($graderContainer);
        }


        $allSumTda = [
            'time' => $autoSumTda['time'] + $graderSumTda['time'],
            'distance' => $autoSumTda['distance'] + $graderSumTda['distance'],
            'area' => $autoSumTda['area'] + $graderSumTda['area'],
        ];

        $res = [
            'code' => 100,
            'msg' => 'success',
            'data' => [
                'all' => $all,
                'all_sum_tda' => $allSumTda,
                'auto_sum_tda' => $autoSumTda,
                'auto_chart' => $autoChartDataSet,
                'grader_sum_tda' => $graderSumTda,
                'grader_chart' => $graderChartDataSet,
            ],
        ];

        return response()->json($res);
    }

    public function mergeSNsTypeTda($typeData, $typeContainer)
    {
        foreach ($typeData as $typeItems) {
            foreach ($typeItems as $typeItem) {
                foreach ($typeItem as $day => $tdaItem) {
                    if (!isset($typeContainer[$day])) {
                        $typeContainer[$day] = $tdaItem;
                    } else {
                        $typeContainer[$day]['time'] += round($typeContainer[$day]['time'] + $tdaItem['time'], 2);
                        $typeContainer[$day]['distance'] += round($typeContainer[$day]['distance'] + $tdaItem['distance'], 2);
                        $typeContainer[$day]['area'] += round($typeContainer[$day]['area'] + $tdaItem['area'], 2);
                    }
                }
            }
        }
        return $typeContainer;
    }

    public function getSumTda($typeContainer)
    {
        $typeSumTda = [
            'time' => 0,
            'distance' => 0,
            'area' => 0,
        ];
        foreach ($typeContainer as $item) {
            $typeSumTda['time'] = round($typeSumTda['time'] + $item['time'], 2);
            $typeSumTda['distance'] += $item['distance'];
            $typeSumTda['area'] += $item['area'];
        }
        return $typeSumTda;
    }

    public function getChartDataSet($typeContainer): array
    {
        $typeChartDataSet = [];
        foreach ($typeContainer as $day => $typeTdaItem) {
            array_push($typeChartDataSet, [
                $day,
                $typeTdaItem['time'],
                $typeTdaItem['distance'],
                $typeTdaItem['area'],
            ]);
        }
        return $typeChartDataSet;
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
                        $fromDateYmd => [
                            'time' => $time,
                            'distance' => $distanceAndArea['distance'],
                            'area' => $distanceAndArea['area'],
                        ],
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

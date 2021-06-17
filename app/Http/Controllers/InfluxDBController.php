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

    public function getWorkExcel(Request $request)
    {
        $workData = $this->getWorkData($request);
    }

    /**
     * 获取工作数据,格式为 ['day' => '2021-03-07', 'time' => 5.7, 'distance' => 37087.67, 'area' => 456.78]
     * @param Request $request
     * @return string|false
     */
    public function getWorkData(Request $request): bool|string
    {
        ini_set('memory_limit', '256M');
        $sn = $request->get('sn', '1140201213940');
        $interval = $request->get('interval', '8h');
        $startDateStr = $request->get('start', '2021-06-01');
        $stopDateStr = $request->get('stop', '2021-06-09');
        $start = gmdate(DATE_RFC3339, strtotime($startDateStr));
        $stop = gmdate(DATE_RFC3339, strtotime($stopDateStr));

        /*$start = strtotime($startDateStr) * 1000 * 1000 * 1000;
        $stop = strtotime($stopDateStr) * 1000 * 1000 * 1000;*/

        $time = $this->getWorkTime($sn, $start, $stop);
        $distanceAndArea = $this->getWorkDistanceAndArea($sn, $start, $stop);

        $data = [];
        // 合并数据
        for ($i = 0; $i < count($time); $i++) {
            if ($time[$i]['day'] == $distanceAndArea[$i]['day']) {
                $arr = [
                    'day' => $time[$i]['day'],
                    'time' => $time[$i]['time'],
                    'distance' => $distanceAndArea[$i]['distance'],
                    'area' => $distanceAndArea[$i]['area'],
                ];
                array_push($data, $arr);
            }
        }
        return json_encode($data);
    }


    /**
     * 获取工作时间数据,格式为 ['day' => '2021-03-07', 'time' => 5.7]
     * @param $sn
     * @param $start
     * @param $stop
     * @param null $auto
     * @param string $interval
     * @return array
     */
    public function getWorkTime($sn, $start, $stop, $auto = null, string $interval = '8h'): array
    {
        $workTimeData = $this->getWorkTimeData($sn, $start, $stop, $auto, $interval);

        $workTime = [];
        $workData = [];

        foreach ($workTimeData as $data) {
            $dateStr = $data->records[0]->values['_start'];
            $count = $data->records[0]->values['_value'];
            $date = date('Y-m-d', strtotime($dateStr));
            array_push($workData, ['day' => $date, 'count' => $count]);
        }

        foreach ($workData as $v) {
            if (!isset($workTime[$v['day']])) {
                $workTime[$v['day']] = $v;
            } else {
                $workTime[$v['day']]['count'] += $v['count'];
            }
        }

        $workTimeHours = [];

        foreach ($workTime as $data) {
            $hours = round($data['count'] / 12 / 60, 2);
            array_push($workTimeHours, ['day' => $data['day'], 'time' => $hours]);
        }

        /*foreach ($workTimeData as $dailyDatum) {
            $dailyDateStr = $dailyDatum[0]->records[0]->values['_start'];
            $dailyDate = date('Y-m-d', strtotime($dailyDateStr));
            $dailyCount = [];
            foreach ($dailyDatum as $data) {
                $workCountEveryEight = $data->records[0]->values['_value'];
                array_push($dailyCount, $workCountEveryEight);
            }
            $dailyCountSum = array_sum($dailyCount);
            $dailyWorkHours = round($dailyCountSum / 12 / 60, 2);
            array_push($workTime, ['day' => $dailyDate, 'time' => $dailyWorkHours]);
        }*/

        return $workTimeHours;
    }


    /**
     * 获取工作距离和面积数据,格式为 ['day' => '2021-03-07', 'distance' => 37087.67, 'area' => 456.78]
     * @param $sn
     * @param $start
     * @param $stop
     * @param null $auto
     * @param string $interval
     * @return array
     */
    public function getWorkDistanceAndArea($sn, $start, $stop, $auto = null, string $interval = '8h'): array
    {
        $workLngAndLatData = $this->getWorkLngAndLatData($sn, $start, $stop, $auto, $interval);
        $dateAndLngLat = [];

        foreach ($workLngAndLatData as $data) {
            $workDateStr = $data->records[0]->values["_start"];
            $workDate = date('Y-m-d', strtotime($workDateStr));
            $records = $data->records;
            $workLonLat = [];
            foreach ($records as $record) {
                $lng = $record->values["lon"];
                $lat = $record->values["lat"];
                $arr = ['lat' => $lat, 'lng' => $lng];
                array_push($workLonLat, $arr);
            }
            array_push($dateAndLngLat, ['day' => $workDate, 'path' => $workLonLat]);
        }
        $workPath = [];
        foreach ($dateAndLngLat as $index => $data) {
            if (!isset($workPath[$data['day']])) {
                $workPath[$data['day']]['path'] = $data['path'];
            } else {
                foreach ($data['path'] as $i => $val) {
                    array_push($workPath[$data['day']]['path'], $data['path'][$i]);
                }

            }
        }

        $distanceAndArea = [];

        foreach ($workPath as $index => $data) {
            $date = $index;
            $pathData = $data['path'];
            $computedData = $this->compute($pathData);
            array_push($distanceAndArea, ['day' => $date, 'distance' => $computedData['distance'], 'area' => $computedData['area']]);
        }

        /* $arr = [
             [
                 'day' => '2021-01-01', 'path' => [
                     ['lat' => 20.00, 'lng' => 20.00],
                     ['lat' => 21.00, 'lng' => 21.00],
                     ['lat' => 22.00, 'lng' => 22.00],
                 ]
             ],
             [
                 'day' => '2021-01-01', 'path' => [
                     ['lat' => 23.00, 'lng' => 23.00],
                     ['lat' => 24.00, 'lng' => 24.00],
                     ['lat' => 25.00, 'lng' => 25.00],
                 ]
             ],
             [
                 'day' => '2021-01-01', 'path' => [
                     ['lat' => 26.00, 'lng' => 26.00],
                     ['lat' => 27.00, 'lng' => 27.00],
                     ['lat' => 28.00, 'lng' => 28.00],
                 ]
             ],
         ];

         $arrTemp = [];

         foreach ($arr as $index => $data) {
             if (!isset($arrTemp[$data['day']])) {
                 $arrTemp[$data['day']]['path'] = $data['path'];
             } else {
                 foreach ($data['path'] as $i => $val) {
                     array_push($arrTemp[$data['day']]['path'], $data['path'][$i]);
                 }

             }
         }*/

        return $distanceAndArea;
    }


    /**
     * 从influxdb中获取原始时间数据
     * @param $sn
     * @param $start
     * @param $stop
     * @param null $auto
     * @param string $interval
     * @return array
     */
    public function getWorkTimeData($sn, $start, $stop, $auto = null, string $interval = '8h'): array
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


    /**
     * 从influxdb中获取原始坐标数据
     * @param $sn
     * @param $start
     * @param $stop
     * @param null $auto
     * @param string $interval
     * @return array
     */
    public function getWorkLngAndLatData($sn, $start, $stop, $auto = null, string $interval = '8h'): array
    {
        if (!isset($auto)) {
            $auto = '0 or r["_value"] == 1';
        }
        $queryStr = 'import "influxdata/influxdb/schema"
            from(bucket: "track")
            |> range(start: ' . $start . ', stop: ' . $stop . ')
            |> filter(fn: (r) => r["_measurement"] == "motion")
            |> filter(fn: (r) => r["sn"] == "' . $sn . '")
            |> schema.fieldsAsCols()
            |> filter(fn: (r) => r["rtkStatus"] == 4)
            |> filter(fn: (r) => r["auto"] == ' . $auto . ')
            |> keep(columns: ["lon", "lat", "_time"])
            |> window(every: ' . $interval . ')';
        return $this->inFluxDB->query($queryStr);
    }


    /**
     * 传入数据,返回距离和面积,格式为 ['lng' => 34.56567, 'lat' => 37.890]
     * @param $path
     * @return array
     */
    public function compute($path): array
    {
        $distance = SphericalUtil::computeLength($path);
        $area = SphericalUtil::computeArea($path);
        return ['distance' => round($distance, 2), 'area' => round((float)$area, 2)];
    }
}

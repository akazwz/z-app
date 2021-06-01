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

    /**
     * 获取工作数据
     * @param Request $request
     * @return JsonResponse
     */
    public function getWorkData(Request $request): JsonResponse
    {
        ini_set('memory_limit', '256M');
        $sn = $request->get('sn', '1140201213940');
        $interval = $request->get('interval', '24h');
        $startDateStr = $request->get('start', '2021-04-01');
        $stopDateStr = $request->get('stop', '2021-05-02');
        $start = gmdate(DATE_ATOM, strtotime($startDateStr));
        $stop = gmdate(DATE_ATOM, strtotime($stopDateStr));

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

        return response()->json($data);
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
    public function getWorkTime($sn, $start, $stop, $auto = null, string $interval = '24h'): array
    {
        $workTimeData = $this->getWorkTimeData($sn, $start, $stop, $auto, $interval);
        $workTime = [];
        foreach ($workTimeData as $data) {
            $workDateStr = $data->records[0]->values['_start'];
            $workDate = date('Y-m-d', strtotime($workDateStr));
            $workCount = $data->records[0]->values['_value'];
            $workHours = round($workCount / 12 / 60, 2);
            array_push($workTime, ['day' => $workDate, 'time' => $workHours]);
        }
        return $workTime;
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
    public function getWorkDistanceAndArea($sn, $start, $stop, $auto = null, string $interval = '24h'): array
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
            array_push($dateAndLngLat, [$workDate, $workLonLat]);
        }
        //2021-04-01,148606,79521
        //2021-04-01,148332.8899477752,79432.1383288722
        $distanceAndArea = [];
        foreach ($dateAndLngLat as $data) {
            $date = $data[0];
            $pathData = $data[1];
            $computedData = $this->compute($pathData);
            array_push($distanceAndArea, ['day' => $date, 'distance' => $computedData['distance'], 'area' => $computedData['area']]);
        }
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


    /**
     * 从influxdb中获取原始坐标数据
     * @param $sn
     * @param $start
     * @param $stop
     * @param null $auto
     * @param string $interval
     * @return array
     */
    public function getWorkLngAndLatData($sn, $start, $stop, $auto = null, string $interval = '24h'): array
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
        return ['distance' => $distance, 'area' => $area];
    }
}

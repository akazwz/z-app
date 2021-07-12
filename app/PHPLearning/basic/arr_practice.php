<?php


namespace App\PHPLearning\basic;

new arr_practice();

class arr_practice
{
    public function __construct()
    {
        $this->test();
    }

    public function test()
    {
        $arr = [
            [
                ['2021-07-02' => ['time' => 0.97, 'distance' => 14884.46, 'area' => 10100.54]],
                ['2021-07-03' => ['time' => 0.37, 'distance' => 9823.95, 'area' => 225.7]],
                ['2021-07-04' => ['time' => 0.1, 'distance' => 5285.56, 'area' => 289.44]],
            ],

            [
                ['2021-07-02' => ['time' => 1.97, 'distance' => 11.46, 'area' => 111.54]],
                ['2021-07-04' => ['time' => 2.97, 'distance' => 111.46, 'area' => 10100.54]],
                ['2021-07-05' => ['time' => 3.97, 'distance' => 456.46, 'area' => 645.54]],
            ],
            [
                ['2021-07-03' => ['time' => 3.97, 'distance' => 456.46, 'area' => 907.54]],
                ['2021-07-04' => ['time' => 5.97, 'distance' => 456.46, 'area' => 897.54]],
                ['2021-07-06' => ['time' => 7.97, 'distance' => 343.46, 'area' => 567.54]],
            ],
        ];
        $res = [];
        foreach ($arr as $item) {
            foreach ($item as $value) {
                foreach ($value as $key => $valueItem) {
                    if (isset($res[$key])) {
                        $newTime = $valueItem['time'];
                        $res[$key]['time'] += $newTime;
                    } else {
                        $res[$key] = $valueItem;
                    }
                }
            }
        }
        print_r($res);
    }
}

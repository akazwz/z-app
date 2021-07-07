<?php


/**
 * 猴子求大王
 * 1、一群猴子排成一圈，按1，2，…，n依次编号。然后从第1只开始数，数到第m只,把它踢出圈，
 * 从它后面再开始数，再数到第m只，在把它踢出去…，如此不停的进行下去，直到最后只剩下一只猴子为止，
 * 那只猴子就叫做大王。要求编程模拟此过程，输入m、n, 输出最后那个大王的编号。
 * @param $m
 * @param $n
 * @return mixed
 * 数组
 */

function kingMonkeys($m, $n): mixed
{
    // 创建 1 到 n 数组
    $monkeys = range(1, $n);
    $i = 0;
    // 循环条件为猴子数量大于1
    while (count($monkeys) > 1) {
        // 猴子为两种, 一种是第 m只, 一种是不是第m只, 不是第m只时, 把当前猴子放在猴群最后面,是第m只时直接踢出
        if (($i + 1) % $m != 0) {
            array_push($monkeys, $monkeys[$i]);
        }
        unset($monkeys[$i]);
        $i++;
    }
    return current($monkeys);
}

//echo kingMonkeys(6, 6);

/**
 * 年牛求牛
 * 2、有一母牛，到4岁可生育，每年一头，所生均是一样的母牛，到15岁绝育，不再能生，20岁死亡，问n年后有多少头牛。
 * @param $years
 * @return int
 * 使用递归, 不断计算出每头牛的产出
 */
function countCows($years): int
{
    // 必须要用static
    static $cows = 1;
    for ($i = 1; $i <= $years; $i++) {
        if ($i >= 4 && $i < 15) {
            $cows++;
            countCows($years - $i);
        }
        if ($i == 20) {
            $cows--;
        }
    }
    return $cows;
}

echo countCows(20);

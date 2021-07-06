<?php


namespace App\PHPLearning\basic;

// 实例化类
new hello();

class hello
{
    // 构造函数,类初始化时调用
    public function __construct()
    {
        //$this->helloWorld();
        $this->ifElseConfuse();
    }

    public function helloWorld()
    {
        echo "Hello, World!";
    }

    // echo true false
    public function ifElseConfuse()
    {
        $a = true;
        if ($a) {
            echo "true";
            echo " ";
        } else label:
        {
            echo "false";
        }
    }
}


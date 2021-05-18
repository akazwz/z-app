<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;

class MyImport implements ToArray
{
    public function array(array $array): array
    {
        return $array;
    }
}

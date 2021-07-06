<?php

$f = $argv[1];
$m = $argv[2];

$filePath = 'basic/' . $f . '.php';

require $filePath;

echo $filePath;

$file = new $f;

$file->$m();



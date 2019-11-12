<?php

require __DIR__ . '/vendor/autoload.php';
\Devrun\Utils\FileTrait::purge($dir = 'temp/cache');

echo "'$dir' is cleared";

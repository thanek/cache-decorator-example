<?php
use xis\DecoratorExample;

ini_set('display_errors', 1);
require_once 'vendor/autoload.php';

$useCache = true;

$t1 = time();
$example = new DecoratorExample($useCache);
$example->doSomeCoolStuff();
$t2 = time();

echo 'Time took: ' . ($t2 - $t1) . ' sec.<br/>';
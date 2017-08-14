<?php

require_once __DIR__ . '/vendor/autoload.php';


use \Analytics\Ga;
use \Analytics\Audience;
use \Analytics\Conversion;
use \Analytics\Behavior;

$audience = new Behavior();
$audience->setViewId("137104990");
$audience->setDate("2017-08-01", "2017-08-07");

echo "<pre>";
print_r($audience->internalSearches());
echo "</pre>";

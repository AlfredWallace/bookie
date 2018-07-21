<?php
$envMode = ' -e test ';
$exec = ' php "%s/../bin/console" ';

passthru(sprintf(
    $exec . 'cache:clear' . $envMode,
    __DIR__
));
passthru(sprintf(
    $exec . 'doctrine:schema:drop  --force' . $envMode,
    __DIR__
));
passthru(sprintf(
    $exec . 'doctrine:schema:update  --force' . $envMode,
    __DIR__
));
passthru(sprintf(
    $exec . 'doctrine:fixtures:load -n' . $envMode,
    __DIR__
));
require __DIR__.'/../vendor/autoload.php';

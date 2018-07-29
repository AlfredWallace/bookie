<?php
$envMode = ' -e test ';
$exec = ' php "%s/../bin/console" ';

passthru(sprintf(
    $exec . 'cache:clear' . $envMode,
    __DIR__
));
passthru(sprintf(
    $exec . 'doctrine:database:drop  --force --if-exists' . $envMode,
    __DIR__
));
passthru(sprintf(
    $exec . 'doctrine:database:create  --if-not-exists' . $envMode,
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

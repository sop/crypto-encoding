<?php

declare(strict_types = 1);

define('TEST_ASSETS_DIR', __DIR__ . '/assets');
require dirname(__DIR__) . '/vendor/autoload.php';
// set default timezone
if (empty(ini_get('date.timezone'))) {
    ini_set('date.timezone', 'UTC');
}

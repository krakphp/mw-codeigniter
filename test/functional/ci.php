<?php

use Krak\Mw;

require_once __DIR__ . '/../../vendor/autoload.php';

$_SERVER['SCRIPT_NAME'] = '/index.php';

chdir(__DIR__ . '/ci2');
require_once 'index.php';

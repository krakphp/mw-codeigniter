<?php

use Krak\Mw;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = mw\diactorosApp();
$app = mw\silentFailApp($app);
$rf = mw\diactorosResponseFactory();
$rf = mw\textResponseFactory($rf);

$app(mw\mwHttpKernel([
    mw\on('/a', function() use ($rf) {
        return $rf(200, [], '/a');
    }),
]));

$_SERVER['SCRIPT_NAME'] = '/index.php';

chdir(__DIR__ . '/ci2');
require_once 'index.php';

<?php

namespace Krak\Mw\Http\Package\CodeIgniter;

use Krak\Mw\Http,
    Pimple;

class CodeIgniterServiceProvider implements Pimple\ServiceProviderInterface
{
    public function register(Pimple\Container $app) {
        $app['codeigniter.ci'] = null;
        $app['codeigniter.server'] = function($app) {
            if (!isset($app['codeigniter.ci'])) {
                throw new InvalidArgumentException('Expected codeigniter.ci parameter to be set');
            }

            return Http\diactorosServer(
                new CIEmitter($app['codeigniter.ci']->output)
            );
        };
        // override the server
        $app['server'] = function($app) {
            return $app['codeigniter.server'];
        };
    }
}

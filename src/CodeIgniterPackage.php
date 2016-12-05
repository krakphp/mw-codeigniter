<?php

namespace Krak\Mw\Http\Package\CodeIgniter;

use Krak\Mw\Http;

class CodeIgniterPackage implements Http\Package
{
    private $ci;
    private $config;

    public function __construct($ci, array $config = []) {
        $this->ci = $ci;
        $this->config = $config + [
            'show_stack_trace' => true,
        ];
    }

    public function with(Http\App $app) {
        $app->register(
            new CodeIgniterServiceProvider(),
            ['codeigniter.ci' => $this->ci]
        );

        $app->push(Http\injectRequestAttribute('ci', $this->ci));
        $app['stacks.exception_handler']->push(ciExceptionHandler($this->config['show_stack_trace']));
        $app['stacks.not_found_handler']->push(ciNotFoundHandler());
        $app['stacks.marshal_response']->push(ciViewMarshalResponse($this->ci));
    }
}

<?php

namespace Krak\Mw\CodeIgniter;

use Krak\Mw,
    Psr\Http\Message\ServerRequestInterface;

function injectCIMw($ci, $param_name = 'ci') {
    return function(ServerRequestInterface $req, $next) use ($ci, $param_name){
        return $next($req->withAttribute($param_name, $ci));
    };
}

function catchExceptionMw($show_stack_trace = true) {
    return Mw\catchException(function($req, $e) use ($show_stack_trace) {
        if (!$show_stack_trace) {
            return show_error('');
        }

        $html = <<<HTML
%s <br/>
<pre>
%s
</pre>
HTML;
        return show_error(sprintf($html, $e->getMessage(), $e));
    });
}

function show404Mw() {
    return function(ServerRequestInterface $req, $next) {
        show_404();
    };
}

/** middleware for wrapping the CI exception catching and 404 handling */
function embeddedCIMw($mw, $show_stack_trace = true) {
    $mw = is_array($mw) ? mw\compose($mw) : $mw;
    return mw\compose([
        catchExceptionMw($show_stack_trace),
        $mw,
        show404Mw()
    ]);
}

/** creates a handle error for the mw-routing component */
function routingHandleError() {
    return function($tup) {
        show_404();
    };
}

/** creates a view marshalResponse for the routing component marshal responses */
function viewMarshalResponse($rf, $ci = null, $param_name = 'ci') {
    return function($tup, $req) use ($rf, $ci, $param_name) {
        $ci = $ci ?: $req->getAttribute($param_name);
        if (!$ci) {
            throw new \RuntimeException('CI instance not found to marshal view response');
        }

        list($view_path, $view_data) = $tup;
        return $rf(200, [], $ci->load->view($view_path, $view_data, true));
    };
}

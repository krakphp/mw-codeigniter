<?php

namespace Krak\Mw\Http\Package {
    function codeIgniter($ci, array $config = []) {
        return new CodeIgniter\CodeIgniterPackage($ci, $config);
    }
}

namespace Krak\Mw\Http\Package\CodeIgniter {
    use Krak\Mw\Http;

    function ciExceptionHandler($show_stack_trace = true) {
        return function($req, $e) use ($show_stack_trace) {
            if (!$show_stack_trace) {
                return show_error($e->getMessage());
            }

            $html = <<<HTML
%s <br/>

<code>
%s
</code>
HTML;
            return show_error(sprintf($html, $e->getMessage(), nl2br($e)));
        };
    }

    function ciNotFoundHandler() {
        return function($req, $res) {
            show_404();
        };
    }

    /** creates a view marshalResponse for the routing component marshal responses */
    function ciViewMarshalResponse($ci) {
        return function($res, $rf, $req, $next) use ($ci) {
            if (!Http\Util\isTuple($res, 'string', 'array')) {
                return $next($res, $rf, $req);
            }

            list($view_path, $view_data) = $res;
            return $rf(200, [], $ci->load->view($view_path, $view_data, true));
        };
    }
}

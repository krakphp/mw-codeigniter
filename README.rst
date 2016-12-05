Mw Http CodeIgniter Integration
===============================

You can integrate the Mw Http framework with CI by embedding an Mw app inside of the CI
framework.

The idea for this integration came from a legacy site I've managed before where we couldn't remove the old CI framework, but we needed to add new features that the Mw Http Framework could solve. So, this allows a nice bridge from an older system to a new one.

Installation
------------

Install with composer at ``krak/mw-codeigniter``

Usage
-----

To have an app that you want to embed inside of the CI framework, you'll need to do a few things.

1. Create a controller to handle the Mw Routes named like `application/controllers/mw.php`
2. Create your mw app inside of the controller method.

.. code-block:: php

    <?php

    use Krak\Mw\Http;

    class Mw extends CI_Controller
    {
        public function index() {
            $app = new Http\App();
            $app->with(Http\Package\std());
            $app->with(Http\Package\codeIgniter($this));

            $app->get('/a', function() {
                return '/a';
            });
            $app->get('/b', function() {
                return '/b';
            });
            $app->get('/exception', function() {
                throw new \InvalidArgumentException('Whoa!!!!');
            });

            $app->serve();
        }
    }

3. Register the default route to point to your mw/index action. All undefined routes will lead to it now.

.. code-block:: php

    <?php

    $route['404_override'] = 'mw';

Make sure to add the CodeIgniter package last or at least later on in the packages.

API
---

These are all relative to the ``Krak\Mw\Http\Package`` namespace.

codeIgniter($ci, array $config = [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

returns a configured instance of ``CodeIgniter\CodeIgniterPackage``. ``$ci`` must be an instance of the Codeigniter controller. ``$config`` is an array that allows the following configuration options:

show_stack_trace
    Forwarded to `ciExceptionHandler`

CodeIgniter\\ciExceptionHandler($show_stack_trace = true)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

An exception handler that will use the CI `show_error` global func to display the exception. ``$show_stack_trace`` determines whether or not to display the stack trace of the exception along with the exception message.

CodeIgniter\\ciNotFoundHandler()
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Simply uses the ``show_404`` method from the CI framework to display a page not found.

CodeIgniter\\ciViewMarshalResponse($ci)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Allows actions to return two-tuples of ``[string, array]`` which represent the view path and the data to load into the view. This internally uses the ``$this->load->view`` method in the CI framework.

CodeIgniter\\CodeIgniterServiceProvider
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The service provider defines the following services:

codeigniter.server
    This is a `Krak\Mw\Http\Server` which serves from inside of the CI framework.
server
    Replaces the `server` parameter with the `codeigniter.server` instance.

**Required Parameters**

codeigniter.ci
    An isntance of CI. This value is automatically filled if you are using the CodeIgniterPackage interface; however, it will need to be set if you are using the service provider on its own.

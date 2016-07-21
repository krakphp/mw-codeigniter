# Mw CodeIgniter Integration

You can integrate the Mw framework with CI by embedding an Mw app inside of the CI
framework, or you can run an Mw app before the CI framework starts.

## Before Integration

To have the Mw app run before the CI framework runs, you have to only define the routes that you need for the mw app and that's it. You can't have a default response handler to return a 404.

```php
<?php

$app = mw\diactorosApp();
// VERY IMPORTANT to use the silentFailApp
$app = mw\silentFailApp($app);

$rf = mw\diactorosResponseFactory();
$rf = mw\textResponseFactory($rf);

// run the app with your own kernel
$app(mw\mwHttpKernel([
    mw\on('/a', function() use ($rf) {
        return $rf(200, [], '/a');
    }),
    // notice how no default handlers are defined
]));

// if there are no routes to be handled, the app will silently fail and let the
// script continue. So now you can load up the CI framework
chdir(__DIR__ . '/path/to/ci/root');
require_once 'index.php';
```

## Embedded Integration

To have an app that you want to embed inside of the CI framework, you'll need to do a few things.

1. Create a controller to handle the Mw Routes named like `application/controllers/mw.php`
2. Create your mw app inside of the controller method.

    ```php
    <?php

    class Mw extends CI_Controller
    {
        public function index() {
            $app = krak\mw\diactorosApp(new Krak\Mw\CodeIgniter\CIEmitter($this->output));
            $rf = krak\mw\diactorosResponseFactory();
            $rf = krak\mw\textResponseFactory($rf);

            $app(krak\mw\mwHttpKernel([
                krak\mw\codeigniter\embeddedCIMw([
                    krak\mw\on('/b', function() use ($rf) {
                        return $rf(200, [], '/b');
                    })
                ])
            ]));
        }
    }
    ```

3. Register the default route to point to your mw/index action. All undefined routes will lead to it now.

    ```php
    <?php

    $route['404_override'] = 'mw';
    ```

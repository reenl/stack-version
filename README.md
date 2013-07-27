# reenl/stack-version

[reenl/stack-version] (https://github.com/reenl/stack-version) provides a Symfony HTTP kernel that allows you to switch
between versions.

## Known issues

Because you will probably have the same classes within different application
versions you might need reenl/stack-mount in order to lazy-load the
different application versions.

## Example

```php
<?php
require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Stack\CallableHttpKernel;
use Stack\Version\CookieVersionSwitch;

$app1 = new CallableHttpKernel(function() {
    return new Response('This is version 1.0!');
});
$app2 = new CallableHttpKernel(function() {
    return new Response('This is version 2.0!');
});

$versionedApp1 = new HttpKernelVersion($app1, '1.0');
$versionedApp2 = new HttpKernelVersion($app2, '2.0');

$app = new CookieVersionSwitch($versionedApp1); // The default version.
$app->add($versionedApp2);

$response = $app->handle(Request::createFromGlobals());
$response->send();
```

If you set a cookie "version" to the value 2.0 you will see the response of
`$app2`. Once the cookie is set the customer will not swap versions untill you
(re)set the cookie, or when you delete version 1.0 from the CookieVersionSwitch.

This way the consumer is guarentied to have the same request as long as the
cookie is valid.


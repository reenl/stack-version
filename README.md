# reenl/stack-version

[reenl/stack-version] (https://github.com/reenl/stack-version) provides a Symfony HTTP kernel that allows you to switch
between versions.

## Example

```php
<?php
require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Stack\CallableHttpKernel;
use Stack\Version\Adapter;
use Stack\Version\VersionSiwtchKernel;

$app1 = new CallableHttpKernel(function() {
    return new Response('This is version 1.0!');
});
$app2 = new CallableHttpKernel(function() {
    return new Response('This is version 2.0!');
});

$adapter = new Adapter(new CookieDetection(), new CookieStore());
$app = new VersionSwitchKernel($versionedApp1, '1.0', array(
    '2.0' => $app2
), $adapter);

$response = $app->handle(Request::createFromGlobals());
$response->send();
```

If you set a cookie "version" to the value 2.0 you will see the response of
`$app2`. Once the cookie is set the customer will not swap versions untill you
(re)set the cookie, or when you delete version 1.0 from the VersionSwitchKernel.

This way the consumer is guaranteed to have the same application version as long
as it's is supported.

## Known issues

Because you will probably have the same classes within different application
versions you might need [stack/lazy-http-kernel]
(https://github.com/stack/lazy-http-kernel) in order to lazy-load the different
application versions.

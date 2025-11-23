<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

if (isset($_SERVER['VERCEL_ENV'])) {
    // Force Laravel to use the Vercel-writable /tmp directory for storage
    $app_base_path = dirname(__DIR__);
    $_SERVER['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
    
    // Create the necessary storage directories if they don't exist
    $storagePath = $_SERVER['LARAVEL_STORAGE_PATH'];
    if (!is_dir("{$storagePath}/framework/cache")) {
        mkdir("{$storagePath}/framework/cache", 0755, true);
    }
    if (!is_dir("{$storagePath}/framework/sessions")) {
        mkdir("{$storagePath}/framework/sessions", 0755, true);
    }
    if (!is_dir("{$storagePath}/framework/views")) {
        mkdir("{$storagePath}/framework/views", 0755, true);
    }
    if (!is_dir("{$storagePath}/logs")) {
        mkdir("{$storagePath}/logs", 0755, true);
    }
}

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);

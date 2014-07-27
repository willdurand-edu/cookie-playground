<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application();

// config
$app['cookie.name']     = 'tracker-cookie-name';
$app['cookie.lifetime'] = 0;

$app->get('/', function () {
    return 'Hello, World!';
});

$app->get('/img.gif', function (Request $request) use ($app) {
    if (!$request->server->has('HTTP_REFERER')) {
        throw new NotFoundHttpException();
    }

    $response = new Response();
    $response->headers->set('Content-Type', 'image/gif');

    if (!$request->cookies->has($app['cookie.name'])) {
        $id = mt_rand();

        $response->headers->setCookie(new Cookie(
            $app['cookie.name'],
            $id,
            $app['cookie.lifetime']
        ));

        error_log(sprintf('New user got unique ID: %d', $id));
    } else {
        $id = $request->cookies->get($app['cookie.name']);

        error_log(sprintf('User with unique ID %d is back!', $id));
    }

    $data = array_merge($request->query->all(), $request->server->all());
    error_log(var_export($data, true));

    // TODO: store data
    // TODO: generate a real gif

    return $response;
});

$app->run();

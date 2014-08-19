<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views',
]);

// config
$app['cookie.name']     = 'tracker-cookie-name';
$app['cookie.lifetime'] = 0;
$app['storage']         = new \YamlStorage(__DIR__ . '/data.yml');


$app->get('/', function (Request $request) use ($app) {
    if (!$request->cookies->has($app['cookie.name'])) {
        return 'No data yet...';
    }

    $id   = $request->cookies->get($app['cookie.name']);
    $data = $app['storage']->get($id);

    return $app['twig']->render('index.html.twig', [
        'trackerId' => $id,
        'data'      => $data,
    ]);
});

$app->get('/img.gif', function (Request $request) use ($app) {
    if (!$request->server->has('HTTP_REFERER')) {
        throw new NotFoundHttpException();
    }

    $response = new GifResponse();

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

    $app['storage']->set($id, new \DateTime(), $data);

    return $response;
});

$app->run();

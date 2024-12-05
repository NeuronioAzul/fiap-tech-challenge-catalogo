<?php

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


$containerBuilder = new ContainerBuilder();
$dependencies = require __DIR__ . '/../dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();
$app = Bridge::create($container);

$app->addErrorMiddleware(true, true, true);
$app->add(new \Slim\Middleware\BodyParsingMiddleware());

$app->get('/openapi', function ($request, $response) {
    $yamlFile = __DIR__ . '/../openapi.yaml';
    $response->getBody()->write(file_get_contents($yamlFile));
    return $response->withHeader('Content-Type', 'application/x-yaml');
});

$app->get('/docs', function ($request, $response) {
    $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>API Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui.css" />
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js" crossorigin></script>
    <script>
        window.onload = () => {
            window.ui = SwaggerUIBundle({
                url: '/openapi',
                dom_id: '#swagger-ui',
            });
        };
    </script>
</body>
</html>
HTML;

    $response->getBody()->write($html);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->post('/produtos', [\App\Infrastructure\API\Controllers\ProdutoController::class, 'criar']);
$app->put('/produtos/{id}', [\App\Infrastructure\API\Controllers\ProdutoController::class, 'atualizar']);
$app->delete('/produtos/{id}', [\App\Infrastructure\API\Controllers\ProdutoController::class, 'deletar']);
$app->get('/produtos', [\App\Infrastructure\API\Controllers\ProdutoController::class, 'listar']);
$app->get('/produtos/{id}', [\App\Infrastructure\API\Controllers\ProdutoController::class, 'obter']);

$app->post('/categorias', [\App\Infrastructure\API\Controllers\CategoriaController::class, 'criar']);
$app->put('/categorias/{id}', [\App\Infrastructure\API\Controllers\CategoriaController::class, 'atualizar']);
$app->delete('/categorias/{id}', [\App\Infrastructure\API\Controllers\CategoriaController::class, 'deletar']);
$app->get('/categorias', [\App\Infrastructure\API\Controllers\CategoriaController::class, 'listar']);
$app->get('/categorias/{id}', [\App\Infrastructure\API\Controllers\CategoriaController::class, 'obter']);

$app->run();

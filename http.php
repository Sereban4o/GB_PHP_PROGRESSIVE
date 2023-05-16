<?php

use GeekBrains\Blog\Exceptions\AppException;
use GeekBrains\Commands\AddLikeComment;
use GeekBrains\Commands\AddLikePost;
use GeekBrains\Http\Actions\Users\FindByUsername;
use GeekBrains\Http\ErrorResponse;
use GeekBrains\Http\HttpException;
use GeekBrains\Http\Request;
use GeekBrains\Http\Actions\Posts\CreatePost;
use GeekBrains\Http\Actions\Posts\CreateComment;
use GeekBrains\Http\Actions\Posts\DeletePost;

require_once __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        /*'/posts/show' => FindByUuid::class,*/

    ],
    'POST' => [
        '/posts/create' => CreatePost::class,
        '/posts/comment' => CreateComment::class,
        '/posts/likes/add' => AddLikePost::class,
        '/comments/likes/add' => AddLikeComment::class,
    ],
    'DELETE' => [
        '/posts' => DeletePost::class,
    ],
];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}

// Получаем имя класса действия для маршрута
$actionClassName = $routes[$method][$path];


// С помощью контейнера
// создаём объект нужного действия
$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}
$response->send();

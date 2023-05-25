<?php

use Dotenv\Dotenv;
use GeekBrains\Http\Auth\AuthenticationInterface;
use GeekBrains\Http\Auth\BearerTokenAuthentication;
use GeekBrains\Http\Auth\JsonBodyUuidAuthentication;
use GeekBrains\Http\Auth\PasswordAuthentication;
use GeekBrains\Http\Auth\PasswordAuthenticationInterface;
use GeekBrains\Http\Auth\TokenAuthenticationInterface;
use GeekBrains\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;
use GeekBrains\Repositories\AuthTokensRepository\SqliteAuthTokensRepository;
use GeekBrains\Repositories\Likes\LikesCommentsRepositoryInterface;
use GeekBrains\Repositories\Likes\LikesPostsRepositoryInterface;
use GeekBrains\Repositories\Likes\SqliteLikesCommentsRepository;
use GeekBrains\Repositories\Likes\SqliteLikesPostsRepository;
use GeekBrains\Repositories\Posts\PostsRepositoryInterface;
use GeekBrains\Repositories\Posts\SqlitePostsRepository;
use GeekBrains\Repositories\Users\SqliteUsersRepository;
use GeekBrains\Repositories\Users\UsersRepositoryInterface;
use GeekBrains\Container\DIContainer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

// Подключаем автозагрузчик Composer
require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

// Создаём объект контейнера ..
$container = new DIContainer();
// .. и настраиваем его:
// 1. подключение к БД
$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH'])
);

$container->bind(
    PostsRepositoryInterface::class,
    SqlitePostsRepository::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    LikesPostsRepositoryInterface::class,
    SqliteLikesPostsRepository::class
);

$container->bind(
    LikesCommentsRepositoryInterface::class,
    SqliteLikesCommentsRepository::class
);

$container->bind(
    AuthenticationInterface::class,
    JsonBodyUuidAuthentication::class
);


// Выносим объект логгера в переменную
$logger = (new Logger('blog'));
// Включаем логирование в файлы,
// если переменная окружения LOG_TO_FILES
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false,
        ));
}
// Включаем логирование в консоль,
// если переменная окружения LOG_TO_CONSOLE
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger
        ->pushHandler(
            new StreamHandler("php://stdout")
        );
}
$container->bind(
    LoggerInterface::class,
    $logger
);

$container->bind(
    AuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);
$container->bind(
    AuthTokensRepositoryInterface::class,
    SqliteAuthTokensRepository::class
);

$container->bind(
    TokenAuthenticationInterface::class,
    BearerTokenAuthentication::class
);



return $container;

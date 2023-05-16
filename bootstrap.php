<?php

use GeekBrains\Repositories\Likes\LikesCommentsRepositoryInterface;
use GeekBrains\Repositories\Likes\LikesPostsRepositoryInterface;
use GeekBrains\Repositories\Likes\SqliteLikesCommentsRepository;
use GeekBrains\Repositories\Likes\SqliteLikesPostsRepository;
use GeekBrains\Repositories\Posts\PostsRepositoryInterface;
use GeekBrains\Repositories\Posts\SqlitePostsRepository;
use GeekBrains\Repositories\Users\SqliteUsersRepository;
use GeekBrains\Repositories\Users\UsersRepositoryInterface;
use GeekBrains\Container\DIContainer;

// Подключаем автозагрузчик Composer
require_once __DIR__ . '/vendor/autoload.php';
// Создаём объект контейнера ..
$container = new DIContainer();
// .. и настраиваем его:
// 1. подключение к БД
$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);
// 2. репозиторий статей
$container->bind(
    PostsRepositoryInterface::class,
    SqlitePostsRepository::class
);
// 3. репозиторий пользователей
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
// Возвращаем объект контейнера
return $container;

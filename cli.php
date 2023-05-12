<?php

use GeekBrains\Blog\Exceptions\AppException;
use GeekBrains\Commands\Arguments;
use GeekBrains\Commands\CreatePostCommand;
use GeekBrains\Repositories\Users\SqliteUsersRepository;
use GeekBrains\Commands\CreateUserCommand;
use GeekBrains\Repositories\Posts\SqlitePostsRepository;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$usersRepository = new SqliteUsersRepository($connection);


$command = new CreateUserCommand($usersRepository);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
    echo "{$e->getMessage()}\n";
}

///Проверка создания поста
$user = $usersRepository->getByUsername("ivan");
$faker = Faker\Factory::create('ru_RU');

$postsRepository = new SqlitePostsRepository($connection);

$postCommand = new CreatePostCommand($postsRepository);

$postCommand->handle($user, $faker);

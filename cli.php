<?php

use GeekBrains\Blog\Exceptions\AppException;
use GeekBrains\Commands\Arguments;
use GeekBrains\Commands\CreateUserCommand;
use Psr\Log\LoggerInterface;


require_once __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/bootstrap.php';

$command = $container->get(CreateUserCommand::class);

$logger = $container->get(LoggerInterface::class);


try {
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
}

///Проверка создания поста
/*user = $usersRepository->getByUsername("ivan");
$faker = Faker\Factory::create('ru_RU');

$postsRepository = new SqlitePostsRepository($connection);

$postCommand = new CreatePostCommand($postsRepository);

$postCommand->handle($user, $faker);*/

<?php

namespace GeekBrains\Commands;

use GeekBrains\Blog\Exceptions\CommandException;
use GeekBrains\Blog\Exceptions\UserNotFoundException;

use GeekBrains\Person\Name;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    // Команда зависит от контракта репозитория пользователей,
    // а не от конкретной реализации
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {
    }
    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");

        $username = $arguments->get('username');
        if ($this->userExists($username)) {
            $this->logger->warning("User already exists: $username");
            return;
        }

        $uuid = UUID::random();

        $this->usersRepository->save(new User(
            $uuid,
            $username,
            new Name($arguments->get('first_name'), $arguments->get('last_name'))
        ));

        $this->logger->info("User created: $uuid");
    }
    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}

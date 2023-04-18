<?php

namespace GeekBrains\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Person\Person;
use GeekBrains\Blog\Exceptions\AppException;

class InMemoryUsersRepository
{
    /**
     * @var Person[]
     */
    private array $users = [];
    /**
     * @param Person $user
     */
    public function save(Person $user): void
    {
        $this->users[] = $user;
    }
    /**
     * @param int $id
     * @return Person
     * @throws UserNotFoundException
     */
    public function get(int $id): Person
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $id");
    }
}

class UserNotFoundException extends AppException
{
}

<?php

namespace GeekBrains\Repositories\Users;

use GeekBrains\Person\UUID, GeekBrains\Person\Name;
use PDO;
use PDOStatement;
use GeekBrains\Blog\Exceptions\UserNotFoundException;

use GeekBrains\Person\User;
use Psr\Log\LoggerInterface;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    ) {
    }

    public function save(User $user): void
    {

        // Подготавливаем запрос
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, username, first_name, last_name)
            VALUES (:uuid, :username, :first_name, :last_name)'
        );

        $user_uuid = $user->uuid();
        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$user_uuid,
            ':username' => $user->username(),
            ':first_name' => $user->name()->first(),
            ':last_name' => $user->name()->last(),
        ]);

        $this->logger->info("User saved: $user_uuid");
    }
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);


        return $this->getUser($statement, $uuid);
    }

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }
    private function getUser(PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->logger->warning("Cannot find user: $username");
            throw new UserNotFoundException(
                "Cannot find user: $username"
            );
        }
        // Создаём объект пользователя с полем username
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            new Name($result['first_name'], $result['last_name'])
        );
    }
}

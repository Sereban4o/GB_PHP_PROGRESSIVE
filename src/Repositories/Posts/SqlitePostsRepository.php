<?php

namespace GeekBrains\Repositories\Posts;

use GeekBrains\Blog\Exceptions\PostNotFoundException;
use GeekBrains\Blog\Post;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Users\SqliteUsersRepository;
use PDO;
use Psr\Log\LoggerInterface;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    ) {
    }

    public function save(Post $post): void
    {

        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
            VALUES (:uuid, :author_uuid, :title, :text)'
        );

        $post_uuid = $post->uuid();

        $statement->execute([
            ':uuid' => (string)$post_uuid,
            ':author_uuid' => (string)$post->getAuthor()->uuid(),
            ':title' => $post->getHeader(),
            ':text' => $post->getText(),
        ]);

        $this->logger->info("Post saved: $post_uuid");
    }
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $this->logger->warning("Cannot find post: $uuid");
            throw new PostNotFoundException(
                "Cannot get post: $uuid"
            );
        }

        $userRepository = new SqliteUsersRepository($this->connection);
        $user = $userRepository->get(new UUID($result['author_uuid']));

        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']
        );
    }

    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM posts WHERE uuid = :uuid;'
        );

        $statement->execute([
            ':uuid' => $uuid,
        ]);
    }
}

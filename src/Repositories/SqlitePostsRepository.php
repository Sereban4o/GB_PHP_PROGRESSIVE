<?php

namespace GeekBrains\Repositories;

use GeekBrains\Blog\Exceptions\PostNotFoundException;
use GeekBrains\Blog\Post;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;
use PDO;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(Post $post): void
    {

        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
            VALUES (:uuid, :author_uuid, :title, :text)'
        );

        $statement->execute([
            ':uuid' => (string)$post->uuid(),
            ':author_uuid' => (string)$post->getAuthor()->uuid(),
            ':title' => $post->getHeader(),
            ':text' => $post->getText(),
        ]);
    }
    public function get(UUID $uuid, User $user): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid and author_uuid = :author_uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
            ':author_uuid' => (string)$user->uuid(),
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new PostNotFoundException(
                "Cannot get post: $uuid"
            );
        }

        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']
        );
    }

    public function getPosts(User $user): void
    {
        //Сделать выбор всех постов автора
    }
}

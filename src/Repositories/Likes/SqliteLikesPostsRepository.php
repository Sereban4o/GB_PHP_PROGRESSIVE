<?php

namespace GeekBrains\Repositories\Likes;


use GeekBrains\Blog\Exceptions\LikeMoreOneException;
use GeekBrains\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\Blog\LikePost;
use GeekBrains\Person\UUID;
use PDO;

class SqliteLikesPostsRepository implements LikesPostsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }
    public function save(LikePost $like): void
    {

        if($this->moreOneLike($like)){
            throw new LikeMoreOneException(
                "Уже есть лайк к статье");
        }

        $statement = $this->connection->prepare(
            'INSERT INTO likes_posts (uuid, user_uuid, post_uuid)
            VALUES (:uuid, :user_uuid, :post_uuid)'
        );

        $statement->execute([
            ':uuid' => (string)$like->getUuid(),
            ':user_uuid' => (string)$like->getUserUuid(),
            ':post_uuid' => (string)$like->getPostUuid(),
        ]);
    }
    public function get(UUID $uuid): LikePost
    {

        $statement = $this->connection->prepare(
            'SELECT * FROM likes_posts WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new LikeNotFoundException(
                "Cannot get like: $uuid"
            );
        }


        return new LikePost(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']),
            new UUID($result['user_uuid'])

        );
    }

    public function getByPostUuid(UUID $post_uuid): LikePost
    {
        $statement = $this->connection->prepare(
        'SELECT * FROM likes_posts WHERE post_uuid = :post_uuid'
    );
        $statement->execute([
            ':post_uuid' => (string)$post_uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new LikeNotFoundException(
                "Cannot get like: $post_uuid"
            );
        }


        return new LikePost(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']),
            new UUID($result['user_uuid'])

        );

    }

    private function moreOneLike(LikePost $like): bool
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes_posts WHERE post_uuid = :post_uuid AND user_uuid = :user_uuid'
        );
        $statement->execute([
            ':post_uuid' => (string)$like->getPostUuid(),
            ':user_uuid' => (string)$like->getUserUuid(),
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            return false;
        }
        return true;
    }
}

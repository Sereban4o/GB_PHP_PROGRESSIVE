<?php

namespace GeekBrains\Repositories\Likes;


use GeekBrains\Blog\Exceptions\LikeMoreOneException;
use GeekBrains\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\Blog\LikeComment;
use GeekBrains\Person\UUID;
use PDO;

class SqliteLikesCommentsRepository implements LikesCommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }
    public function save(LikeComment $like): void
    {

        if($this->moreOneLike($like)){
            throw new LikeMoreOneException(
                "Уже есть лайк к комментарию");
        }

        $statement = $this->connection->prepare(
            'INSERT INTO likes_comments (uuid, user_uuid, comment_uuid)
            VALUES (:uuid, :user_uuid, :comment_uuid)'
        );

        $statement->execute([
            ':uuid' => (string)$like->getUuid(),
            ':user_uuid' => (string)$like->getUserUuid(),
            ':comment_uuid' => (string)$like->getCommentUuid(),
        ]);
    }
    public function get(UUID $uuid): LikeComment
    {

        $statement = $this->connection->prepare(
            'SELECT * FROM likes_comments WHERE uuid = :uuid'
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


        return new LikeComment(
            new UUID($result['uuid']),
            new UUID($result['comment_uuid']),
            new UUID($result['user_uuid'])

        );
    }


    private function moreOneLike(LikeComment $like): bool
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes_comments WHERE comment_uuid = :comment_uuid AND user_uuid = :user_uuid'
        );
        $statement->execute([
            ':comment_uuid' => (string)$like->getCommentUuid(),
            ':user_uuid' => (string)$like->getUserUuid(),
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            return false;
        }
        return true;
    }

    public function getByCommentUuid(UUID $comment_uuid): LikeComment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes_comments WHERE comment_uuid = :comment_uuid'
        );
        $statement->execute([
            ':comment_uuid' => (string)$comment_uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new LikeNotFoundException(
                "Cannot get like: $comment_uuid"
            );
        }


        return new LikeComment(
            new UUID($result['uuid']),
            new UUID($result['comment_uuid']),
            new UUID($result['user_uuid'])

        );
    }
}

<?php

namespace GeekBrains\Repositories\Comments;

use GeekBrains\Blog\Comment;
use GeekBrains\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\Blog\Post;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Posts\SqlitePostsRepository;
use GeekBrains\Repositories\Users\SqliteUsersRepository;
use PDO;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }
    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
            VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

        $statement->execute([
            ':uuid' => (string)$comment->uuid(),
            ':post_uuid' => (string)$comment->getPost()->uuid(),
            ':author_uuid' => (string)$comment->getAuthor()->uuid(),
            ':text' => $comment->getText(),
        ]);
    }
    public function get(UUID $uuid): Comment
    {

        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new CommentNotFoundException(
                "Cannot get post: $uuid"
            );
        }

        $userRepository = new SqliteUsersRepository($this->connection);
        $user = $userRepository->get(new UUID($result['author_uuid']));

        $postRepository = new SqlitePostsRepository($this->connection);
        $post = $postRepository->get(new UUID($result['post_uuid']));


        return new Comment(
            new UUID($result['uuid']),
            $user,
            $post,
            $result['text']
        );
    }
}

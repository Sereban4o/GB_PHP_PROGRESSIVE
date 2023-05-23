<?php

namespace GeekBrains\Commands;

use GeekBrains\Blog\Comment;
use GeekBrains\Blog\Post;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Comments\CommentsRepositoryInterface;

class CreateCommentCommand
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository
    ) {
    }

    public function handle(User $user, Post $post, $faker): void
    {
        $this->commentsRepository->save(new Comment(
            UUID::random(),
            $user,
            $post,
            $faker->text(200)
        ));
    }
}

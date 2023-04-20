<?php

namespace GeekBrains\Repositories;

use GeekBrains\Blog\Comment;
use GeekBrains\Blog\Post;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid, User $user, Post $post): Comment;
}

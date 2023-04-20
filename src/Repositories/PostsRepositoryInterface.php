<?php

namespace GeekBrains\Repositories;

use GeekBrains\Blog\Post;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;

    public function get(UUID $uuid, User $user): Post;
}

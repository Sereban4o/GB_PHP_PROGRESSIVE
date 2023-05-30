<?php

namespace GeekBrains\Repositories\Posts;

use GeekBrains\Blog\Post;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;

    public function get(UUID $uuid): Post;

    public function delete(UUID $uuid): void;
}

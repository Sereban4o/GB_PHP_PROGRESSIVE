<?php

namespace GeekBrains\Repositories\Likes;


use GeekBrains\Blog\LikePost;
use GeekBrains\Person\UUID;

interface LikesPostsRepositoryInterface
{
    public function save(LikePost $like): void;
    public function get(UUID $uuid): LikePost;
    public function getByPostUuid(UUID $post_uuid): LikePost;
}

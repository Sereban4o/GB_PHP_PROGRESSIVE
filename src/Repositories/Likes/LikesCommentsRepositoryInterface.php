<?php

namespace GeekBrains\Repositories\Likes;


use GeekBrains\Blog\LikeComment;
use GeekBrains\Person\UUID;

interface LikesCommentsRepositoryInterface
{
    public function save(LikeComment $like): void;
    public function get(UUID $uuid): LikeComment;
    public function getByCommentUuid(UUID $comment_uuid): LikeComment;
}

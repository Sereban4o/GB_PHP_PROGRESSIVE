<?php

namespace GeekBrains\Commands;

use GeekBrains\Blog\LikeComment;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Likes\LikesCommentsRepositoryInterface;


readonly class AddLikeComment
{
    public function __construct(
        private LikesCommentsRepositoryInterface $likesRepository
    ) {
    }

    public function handle($comment_uuid, $user_uuid): void
    {


        $this->likesRepository->save(new LikeComment(
            UUID::random(),
            new UUID($comment_uuid),
            new UUID($user_uuid)
        ));
    }
}

<?php

namespace GeekBrains\Commands;

use GeekBrains\Blog\LikePost;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Likes\LikesPostsRepositoryInterface;


readonly class AddLikePost
{
    public function __construct(
        private LikesPostsRepositoryInterface $likesRepository
    ) {
    }

    public function handle($post_uuid, $user_uuid): void
    {


        $this->likesRepository->save(new LikePost(
            UUID::random(),
            new UUID($post_uuid),
            new UUID($user_uuid)
        ));
    }
}

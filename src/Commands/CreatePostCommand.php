<?php

namespace GeekBrains\Commands;

use GeekBrains\Blog\Post;
use GeekBrains\Person\User;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Posts\PostsRepositoryInterface;

class CreatePostCommand
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    public function handle(User $user, $faker): void
    {


        $this->postsRepository->save(new Post(
            UUID::random(),
            $user,
            $faker->text(20),
            $faker->text(200)
        ));
    }
}

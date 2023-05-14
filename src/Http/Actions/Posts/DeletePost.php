<?php

namespace GeekBrains\Http\Actions\Posts;

use GeekBrains\Blog\Exceptions\PostNotFoundException;
use GeekBrains\Http\ActionInterface;
use GeekBrains\Http\ErrorResponse;
use GeekBrains\Http\Request;
use GeekBrains\Http\Response;
use GeekBrains\Http\SuccessfulResponse;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Posts\PostsRepositoryInterface;

readonly class DeletePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    )
    {
    }


    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');
            $this->postsRepository->get(new UUID($postUuid));

        } catch (PostNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $this->postsRepository->delete(new UUID($postUuid));

        return new SuccessfulResponse([
            'uuid' => $postUuid,
        ]);
    }
}
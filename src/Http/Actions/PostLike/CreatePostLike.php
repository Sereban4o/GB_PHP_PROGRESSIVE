<?php

namespace GeekBrains\Http\Actions\PostLike;

use GeekBrains\Blog\Exceptions\PostNotFoundException;
use GeekBrains\Blog\LikePost;
use GeekBrains\Http\ActionInterface;
use GeekBrains\Http\Auth\AuthException;
use GeekBrains\Http\Auth\TokenAuthenticationInterface;
use GeekBrains\Http\ErrorResponse;
use GeekBrains\Http\HttpException;
use GeekBrains\Http\Request;
use GeekBrains\Http\Response;
use GeekBrains\Http\SuccessfulResponse;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Likes\LikesPostsRepositoryInterface;
use GeekBrains\Repositories\Posts\PostsRepositoryInterface;

class CreatePostLike implements ActionInterface
{
    public   function __construct(
        private LikesPostsRepositoryInterface $likesRepository,
        private PostsRepositoryInterface $postRepository,
        private TokenAuthenticationInterface $authentication,
    ) {
    }


    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->user($request);
        } catch (AuthException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        try {
            $post_uuid = $request->JsonBodyField('post_uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }


        try {
            $this->postRepository->get(new UUID($post_uuid));
        } catch (PostNotFoundException $exception) {
            return new ErrorResponse($exception->getMessage());
        }



        $uuid = UUID::random();

        $like = new LikePost(
            uuid: $uuid,
            post_uuid: new UUID($postUuid),
            user_uuid: $author->uuid(),

        );

        $this->likesRepository->save($like);

        return new SuccessfulResponse(
            ['uuid' => (string)$uuid]
        );
    }
}
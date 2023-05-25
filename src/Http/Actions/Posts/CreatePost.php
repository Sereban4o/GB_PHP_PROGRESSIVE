<?php
namespace GeekBrains\Http\Actions\Posts;

use GeekBrains\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\Blog\Exceptions\UserNotFoundException;
use GeekBrains\Blog\Post;
use GeekBrains\Http\ActionInterface;
use GeekBrains\Http\Auth\AuthenticationInterface;
use GeekBrains\Http\Auth\AuthException;
use GeekBrains\Http\Auth\TokenAuthenticationInterface;
use GeekBrains\Http\ErrorResponse;
use GeekBrains\Http\HttpException;
use GeekBrains\Http\Request;
use GeekBrains\Http\Response;
use GeekBrains\Http\SuccessfulResponse;
use GeekBrains\Person\UUID;
use GeekBrains\Repositories\Posts\PostsRepositoryInterface;
use GeekBrains\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

readonly class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private TokenAuthenticationInterface $authentication,
        private LoggerInterface          $logger,

    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newPostUuid = UUID::random();
        try {
            $post = new Post(
                $newPostUuid,
                $author,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->save($post);

        $this->logger->info("Post created: $newPostUuid");

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}

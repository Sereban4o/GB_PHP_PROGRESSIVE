<?php
namespace GeekBrains\Http\Actions\Posts;

use GeekBrains\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\Blog\Exceptions\UserNotFoundException;
use GeekBrains\Blog\Post;
use GeekBrains\Http\ActionInterface;
use GeekBrains\Http\Auth\IdentificationInterface;
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
        private IdentificationInterface $identification,
        // Внедряем контракт логгера
        private LoggerInterface $logger,

    ) {
    }
    public function handle(Request $request): Response
    {
        $author = $this->identification->user($request);

       /* try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }*/

        $newPostUuid = UUID::random();
        try {
            $post = new Post(
                $newPostUuid,
                $author->uuid(),
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

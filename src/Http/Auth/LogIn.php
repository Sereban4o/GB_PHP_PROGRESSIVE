<?php
namespace GeekBrains\Http\Auth;

use DateTimeImmutable;
use GeekBrains\Blog\AuthToken;
use GeekBrains\Http\ActionInterface;
use GeekBrains\Http\Auth\AuthException;
use GeekBrains\Http\Auth\PasswordAuthenticationInterface;
use GeekBrains\Http\ErrorResponse;
use GeekBrains\Http\Request;
use GeekBrains\Http\Response;
use GeekBrains\Http\SuccessfulResponse;
use GeekBrains\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;

class LogIn implements ActionInterface
{
    public function __construct(
// Авторизация по паролю
        private PasswordAuthenticationInterface $passwordAuthentication,
// Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }
    public function handle(Request $request): Response
    {
// Аутентифицируем пользователя
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }
// Генерируем токен
        $authToken = new AuthToken(
// Случайная строка длиной 40 символов
            bin2hex(random_bytes(40)),
            $user->uuid(),
// Срок годности - 1 день
            (new DateTimeImmutable())->modify('+1 day')
        );
// Сохраняем токен в репозиторий
        $this->authTokensRepository->save($authToken);
// Возвращаем токен
        return new SuccessfulResponse([
            'token' => (string)$authToken,
        ]);
    }
}
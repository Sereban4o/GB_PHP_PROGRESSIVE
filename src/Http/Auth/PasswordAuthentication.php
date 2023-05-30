<?php

namespace GeekBrains\Http\Auth;

use GeekBrains\Blog\Exceptions\UserNotFoundException;
use GeekBrains\Http\HttpException;
use GeekBrains\Http\Request;
use GeekBrains\Person\User;
use GeekBrains\Repositories\Users\UsersRepositoryInterface;

class PasswordAuthentication implements PasswordAuthenticationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
    public function user(Request $request): User
    {
// 1. Идентифицируем пользователя
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
// 2. Аутентифицируем пользователя
// Проверяем, что предъявленный пароль
// соответствует сохранённому в БД
        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!$user->checkPassword($password)) {
            throw new AuthException('Wrong password');
        }

// Пользователь аутентифицирован
        return $user;
    }
}

<?php

require_once __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Person\Person;

/*use src\Blog\Post;
use src\Person\Name;
use src\Person\Person;*/

/*spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists(($file))) {
        require $file;
    }
});*/

/* $post = new Post(
    1,
    new Person(
        1,
        new Name('Иван', 'Никитин'),
        new DateTimeImmutable()
    ),
    'Заголовок',
    'Всем привет!'
);
print $post;
 */

$faker = Faker\Factory::create('ru_RU');

if ($argv > 1) {
    switch ($argv[1]) {
        case 'user':
            print getFakerUser($faker);
            break;
        case 'post':
            print getFakerPost($faker, getFakerUser($faker));
            break;
        case 'comment':
            $user = getFakerUser($faker);
            print getFakerComment($faker, $user, getFakerPost($faker, $user));
            break;
    }
}

function getFakerUser($faker)
{
    $firstname = $faker->firstName();
    $lastname = $faker->lastName();
    $user = new Person(1, new Name($firstname, $lastname), new DateTimeImmutable());
    return $user;
}

function getFakerPost($faker, $user)
{
    $text = $faker->text(200);
    $header = $faker->text(20);

    $post = new Post(1, $user, $header, $text);
    return $post;
}

function getFakerComment($faker, $user, $post)
{

    $textComment = $faker->text(100);
    $comment = new Comment(1, $user, $post, $textComment);

    return $comment;
}

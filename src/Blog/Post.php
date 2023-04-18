<?php

//namespace src\Blog;

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Person;

class Post
{
    public function __construct(
        private int $id,
        private Person $author,
        private string $header,
        private string $text
    ) {
    }
    public function __toString()
    {
        return $this->header . ' >>> ' . $this->text;
    }
}

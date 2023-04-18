<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Person;

class Comment
{
    public function __construct(
        private int $id,
        private Person $author,
        private Post $post,
        private string $text
    ) {
    }

    public function __toString()
    {
        return $this->text;
    }
}

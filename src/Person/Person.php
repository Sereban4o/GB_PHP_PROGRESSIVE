<?php

//namespace src\Person;

namespace GeekBrains\LevelTwo\Person;

use DateTimeImmutable;
use GeekBrains\LevelTwo\Person\Name;

class Person
{
    public function __construct(
        private int $id,
        private Name $name,
        private DateTimeImmutable $registeredOn
    ) {
    }
    public function __toString()
    {
        return $this->name->__toString();
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }
}

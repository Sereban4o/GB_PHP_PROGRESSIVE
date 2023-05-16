<?php

namespace GeekBrains\Person;


class User
{
    public function __construct(
        private UUID $uuid,
        private string $username,
        private Name $name
    ) {
    }
    public function __toString()
    {
        return $this->name->__toString();
    }

    /**
     * Get the value of id
     */
    public function uuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * Get the value of name
     */
    public function name(): Name
    {
        return $this->name;
    }

    /**
     * Get the value of username
     */
    public function username(): string
    {
        return $this->username;
    }
}

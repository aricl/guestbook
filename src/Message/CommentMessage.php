<?php

namespace App\Message;

class CommentMessage
{
    private string $id;
    private array $context;

    public function __construct(string $id, array $context = [])
    {
        $this->id = $id;
        $this->context = $context;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}

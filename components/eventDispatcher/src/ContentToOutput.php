<?php

namespace Event\EventDispatcher;

class ContentToOutput
{
    public function __construct(private string $content = '')
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}

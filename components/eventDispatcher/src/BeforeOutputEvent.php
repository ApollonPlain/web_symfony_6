<?php

declare(strict_types=1);

namespace Event\EventDispatcher;

use Symfony\Contracts\EventDispatcher\Event;

class BeforeOutputEvent extends Event
{
    public const NAME = 'before.output';

    protected ContentToOutput $content;

    public function __construct(ContentToOutput $content)
    {
        $this->validateContent($content);
        $this->content = $content;
    }

    public function getOutputContent(): ContentToOutput
    {
        return $this->content;
    }

    public function transformContent(callable $transformer): void
    {
        $newContent = $transformer($this->content->getContent());
        $this->content->setContent($newContent);
    }

    private function validateContent(ContentToOutput $content): void
    {
        if (empty($content->getContent())) {
            throw new \InvalidArgumentException('Content cannot be empty');
        }
    }
}

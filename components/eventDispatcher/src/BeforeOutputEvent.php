<?php

namespace Event\EventDispatcher;

use Symfony\Contracts\EventDispatcher\Event;

class BeforeOutputEvent extends Event
{
    public const NAME = 'before.output';

    protected $content;

    public function __construct(ContentToOutput $content)
    {
        $this->content = $content;
    }

    public function getOutputContent()
    {
        return $this->content;
    }
}

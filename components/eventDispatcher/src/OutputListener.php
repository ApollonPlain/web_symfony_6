<?php

namespace Event\EventDispatcher;

class OutputListener
{
    public function onBeforeOutput(BeforeOutputEvent $event)
    {
        $content = $event->getOutputContent();
        $out = $content->getContent();
        $content->setContent('Listener ('.$out.')');
    }
}

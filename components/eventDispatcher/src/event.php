<?php

namespace Event\EventDispatcher;

require_once __DIR__.'/../vendor/autoload.php';
require_once 'ContentToOutput.php';
require_once 'BeforeOutputEvent.php';
require_once 'OutputListener.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\Event;

$dispatcher = new EventDispatcher();

// class AcmeListener
// {
//    public function onFooAction(Event $event): string
//    {
//        return 'Foo';
//    }
// }
//
// $listener = new AcmeListener();
// $dispatcher->addListener('acme.foo.action', [$listener, 'onFooAction']);
//
// //
$msg = new ContentToOutput('hello');

$outputListener = new OutputListener();
$dispatcher->addListener('before.output', [$outputListener, 'onBeforeOutput']);

$dispatcher->dispatch(new BeforeOutputEvent($msg), BeforeOutputEvent::NAME);

echo $msg->getContent();

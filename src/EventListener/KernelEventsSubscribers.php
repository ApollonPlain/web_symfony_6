<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents as KernelEventsList;

class KernelEventsSubscribers implements EventSubscriberInterface
{
    use KernelEvents;

    public static function getSubscribedEvents()
    {
        return [
            KernelEventsList::REQUEST => [
                ['onKernelRequest', 20],
            ],
        ];
    }
    //        dd('here');
}

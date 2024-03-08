<?php

namespace App\EventListener;

use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents as KernelEventsList;

trait KernelEvents
{
    public function __construct(
        //        HttpKernelInterface $kernel,
        //        \Symfony\Component\HttpFoundation\Request $request,
        //        ?int $requestType,
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack
    ) {
        //        parent::__construct($kernel, $request, $requestType);
    }

    //    #[AsEventListener(event: KernelEventsList::REQUEST, priority: 1)]
    public function onKernelRequest(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        $requestEntity = new Request();

        $requestEntity->setIsMainResquest($request == $this->requestStack->getMainRequest());
        $requestEntity->setData((array) $request->getContent());
        $requestEntity->setRoute($request->getRequestUri());

        $this->entityManager->persist($requestEntity);
        $this->entityManager->flush();
    }
}

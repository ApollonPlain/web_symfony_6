<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/child')]
final class ChildController extends AbstractController
{
    #[Route('/{value}')]
    public function index(int $value): Response
    {
        //        $this->container->get('quizservice').

        if ($value > 0) {
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'title' => 'Home',
            ]);
        }

        $twig = $this->renderView('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'title' => 'Home',
        ]);

        return new Response($twig);
    }
}

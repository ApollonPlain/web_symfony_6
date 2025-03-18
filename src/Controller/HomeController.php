<?php

namespace App\Controller;

use App\Security\AutoLoginService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AutoLoginService $autoLoginService): Response
    {
        // Try to auto login the default user
        $isLoggedIn = $autoLoginService->autoLogin();

        if ($isLoggedIn) {
            $this->addFlash('success', 'Vous avez été automatiquement connecté en tant qu\'utilisateur par défaut.');
        }

        return $this->redirectToRoute('app_quiz_index');
    }
}

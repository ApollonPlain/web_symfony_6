<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class AutoLoginService
{
    private UserRepository $userRepository;
    private Security $security;
    private RequestStack $requestStack;

    public function __construct(
        UserRepository $userRepository,
        Security $security,
        RequestStack $requestStack,
    ) {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    /**
     * Auto login with the default user.
     *
     * @return bool True if login was successful
     */
    public function autoLogin(): bool
    {
        // Skip if user is already logged in
        if (null !== $this->security->getUser()) {
            return false;
        }

        // Find default user
        $user = $this->userRepository->findOneBy(['email' => 'user@example.com']);

        if (!$user) {
            return false;
        }

        // Login the user
        $this->security->login($user, 'app_authenticator');

        // Store last username in session like a normal login would
        $session = $this->requestStack->getSession();
        $session->set('_security.last_username', $user->getEmail());

        return true;
    }
}

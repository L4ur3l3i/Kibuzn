<?php

namespace Kibuzn\Controller\App;

use Kibuzn\Exception\UnverifiedAccountException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // If the user is already logged in, redirect to the homepage
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_index');
        }

        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Get the last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Handle different error types
        if ($error) {
            if ($error instanceof BadCredentialsException) {
                // Handle invalid credentials
                $this->addFlash(
                    'error',
                    [
                        'message' => 'Invalid credentials.',
                        'description' => 'The combination of email and password you entered did not match our records. Please double-check and try again or reset your password.',
                        'rightlink' => [
                            'label' => 'Forgot Password?',
                            'route' => 'https://example.com/forgot-password',
                        ],
                    ]
                );
            } elseif ($error instanceof UnverifiedAccountException) {
                // Handle unverified account
                $this->addFlash(
                    'warning',
                    [
                        'message' => 'Account not verified.',
                        'description' => 'You need to verify your email address before you can log in. Please check your inbox or request a new verification email.',
                        'rightlink' => [
                            'label' => 'Resend Verification Email',
                            'route' => $this->generateUrl('app_resend_verification_email') . '?email=' . $lastUsername,
                        ],
                    ]
                );
            } else {
                // General authentication error
                $this->addFlash(
                    'error',
                    [
                        'message' => 'Authentication error.',
                        'description' => 'There was an issue with your login attempt. Please try again later or contact support.',
                    ]
                );
            }
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

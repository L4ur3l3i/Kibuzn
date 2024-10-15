<?php

namespace Kibuzn\Controller\App;

use Doctrine\ORM\EntityManagerInterface;
use Kibuzn\Entity\User;
use Kibuzn\Form\RegistrationFormType;
use Kibuzn\Repository\UserRepository;
use Kibuzn\Security\EmailVerifier;
use Kibuzn\Service\MediaService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager
    ): Response {

        // If the user is already logged in, redirect to the homepage
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_index');
        }
        
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            // Handle the plain password
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
    
            // Set user role
            $user->setRoles(['ROLE_USER']);
    
            // Save the user to the database to generate the user ID
            $entityManager->persist($user);
            $entityManager->flush(); // Now the user has an ID
    
            // Handle the uploaded avatar after the user is flushed
            $avatar = $form->get('avatar')->getData(); // This returns an UploadedFile object
    
            if ($avatar) {
                // Use MediaService to handle the avatar file upload, now we have the user ID
                $filename = MediaService::uploadMedia('avatar', $avatar, $user->getId());
    
                if ($filename) {
                    $user->setAvatar($filename);
                    $entityManager->persist($user); // Update the user with avatar path
                    $entityManager->flush();
                }
            }
    
            // Generate a signed URL and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mailer@kibuzn.org', 'Kibuzn'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('email/user/email_confirmation.html.twig')
                    ->context([
                        'user' => $user,
                    ])
            );

            $this->addFlash(
                'success',
                [
                    'message' => 'Registration successful.',
                    'description' => 'A verification email has been sent to your email address. Please check your inbox and follow the instructions to verify your email.',
                ]
            );
    
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        if ($user->getVerifiedAt() !== null) {
            return $this->redirectToRoute('app_dashboard_index');
        }

        // validate email confirmation link, sets User::verified_at and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash(
                'error',
                [
                    'message' => 'Email verification failed.',
                    'description' => $translator->trans($exception->getReason()
                        ?: 'An error occurred while verifying your email address.'),
                ]
            );
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash(
            'success',
            [
                'message' => 'Email verified.',
                'description' => 'Your email address has been verified successfully. You can now log in to your account.',
            ]
        );

        return $this->redirectToRoute('app_dashboard_index');
    }

    #[Route('/verify/email/resend', name: 'app_resend_verification_email')]
    public function resendEmailVerification(Request $request, UserRepository $userRepository): Response
    {
        $email = $request->query->get('email');

        if ($email === null) {
            $this->addFlash(
                'error', 
                [
                    'message' => 'Invalid request.',
                    'description' => 'Please provide a valid email address to resend the verification email.',
                ]
            );
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user === null) {
            $this->addFlash(
                'error', 
                [
                    'message' => 'User not found.',
                    'description' => 'The email address provided does not match any user in our records.',
                ]
            );
            return $this->redirectToRoute('app_register');
        }

        // Generate a signed URL and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
        (new TemplatedEmail())
            ->from(new Address('mailer@kibuzn.org', 'Kibuzn'))
            ->to((string) $user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('email/user/email_confirmation.html.twig')
            ->context([
                'user' => $user,
            ])
        );

        $this->addFlash(
            'success', 
            [
                'message' => 'Verification email sent.',
                'description' => 'A new verification email has been sent to your email address. Please check your inbox and follow the instructions to verify your email.',
            ]
        );

        return $this->redirectToRoute('app_login');
    }
}

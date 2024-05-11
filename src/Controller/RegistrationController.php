<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,JWTService $jwt,SendEmailService $mail): Response
    {   
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //$user->setRoles(['ROLE_ADMIN']);
            $entityManager->persist($user);
            $entityManager->flush();


            //token
            $header=[
                'typ'=>'JWT',
                'alg'=>'HS256'
            ];
            $payload=[
                'user_id'=>$user->getId(),
            ];
            $token=$jwt->generate($header,$payload,$this->getParameter('app.JWT'));
            $mail->send(
                'eyamellehi@gmail.com',
                $user->getEmail(),
                'Activation de votre compte',
                'confirmation_email',
                compact('user','token')
            );
            $this->addFlash('success','utilisateur inscrit,veuillez cliquer sur le lien recu pour confirmer votre adresse email');  

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/{token}', name: 'app_verify_email')]
    public function verifyUserEmail($token,JWTService $jwt,Request $request, TranslatorInterface $translator, UserRepository $userRepository,EntityManagerInterface $em): Response
    {   if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check(
        $token,$this->getParameter('app.JWT'))){
            $payload=$jwt->getPayload($token);
            $user=$userRepository->find($payload['user_id']);
            if($user && !$user->isVerified()){
                $user->setVerified(true);
                $em->flush();
                $this->addFlash('success','utilisateur active');
                return $this->redirectToRoute('app_home');
            }
            }
            $this->addFlash('danger','le token est invalid');
            return $this->redirectToRoute('app_register');
        }
    }
        /*$id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_home');
    }*/

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\ResetpassType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

//use Symfony\Component\BrowserKit\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
         }
            
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        
        return $this->redirectToRoute('app_login');
    }
    //motdepasseoublier
    #[Route(path: '/mot-de-passe-oublier', name: 'passe_oublier')]
    public function motdepasseoublier(Request $req,UserRepository $userRep,JWTService $jwt,SendEmailService $mail): Response
    {   
        $form = $this->createForm(ResetpassType::class);        
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) { 
            $user=$userRep->findonebyEmail($form->get('email')->getData());
            if($user){
                $header=[
                    'typ'=>'JWT',
                    'alg'=>'HS256'
                ];
                $payload=[
                    'user_id'=>$user->getId(),
                ];
                $token=$jwt->generate($header,$payload,$this->getParameter('app.JWT'));
                //on genere URL vers rest pass
                $url=$this->generateUrl('reset_pass',['token'=>$token],UrlGeneratorInterface::ABSOLUTE_URL); 
                $mail->send(
                    'eyamellehi@gmail.com',
                    $user->getEmail(),
                    'Recuperation de mot de passe SymBokk',
                    'password_reset',
                    compact('user','url')
                );
                $this->addFlash('Success','Email envoyee avec succes');
                return $this->redirectToRoute('app_login');
            }
            //user null
            $this->addFlash('danger','un probleme est servenu');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/motdepasseoublier.html.twig', [
            'form' => $form->createView(),
        ]);        
    }
    #[Route(path: '/mot-de-passe-oublier/{token}', name: 'reset_pass')]
    public function ResetPasst(EntityManagerInterface $em ,UserPasswordHasherInterface $hash,Request $req,JWTService $jwt,$token,UserRepository $u): Response
    {   
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check(
        $token,$this->getParameter('app.JWT'))){

            $payload=$jwt->getPayload($token);
           
            $user=$u->find($payload['user_id']);
           //dd($user);
            if($user){
                $form=$this->createForm(ResetPassFormType::class);
                $form->handleRequest($req);
                if($form->isSubmitted() && $form->isValid()){
                    $user->setPassword(
                        $hash->hashPassword($user,$form->get('password')->getData())
                    );
                    $em->flush();
                $this->addFlash('success','Motde passe changer');
                return $this->redirectToRoute('app_login');
            }
            return $this->render('security/reset_pass.html.twig', [
                'form' => $form->createView(),
            ]);  
            }
        
        }
        $this->addFlash('danger','le token est invalid');
        return $this->redirectToRoute('app_login');
    }

}

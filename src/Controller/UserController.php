<?php

namespace App\Controller;

use App\Form\UserUpdatesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
  
    #[Route('/User/mise-a-jour', name: 'user_profile_update')]
    public function updateProfile(Request$req,EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserUpdatesType::class, $user);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) { 
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_profile_update');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    }

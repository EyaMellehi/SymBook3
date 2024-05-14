<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdatesType;
use App\Repository\UserRepository;
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
    #[Route('/Admin/users', name: 'admin_users')]
    public function index(UserRepository $u): Response
    {
        $users = $u->findAll();
        return $this->render('user/users.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/Admin/users/delete/{id}', name: 'admin_users_delete')]
    public function delete(EntityManagerInterface $em,User $user): Response
    {    //recherche du livre à supprimer

        //suppression du livre trouvé
        $em->remove($user);
        $em->flush();
        //dd($livre);

        return $this->redirectToRoute('admin_users');
    }
    }

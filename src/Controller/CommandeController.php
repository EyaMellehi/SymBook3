<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livres;
use App\Repository\LivresRepository;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(LivresRepository $livresRepository): Response
    {   
        $livres = $livresRepository->findAll();
        return $this->render('commande/index.html.twig', [
            'livres' => $livres,
        ]);
    }

    #[Route('/User/livres/commande/{id}', name: 'User_livres_commande')]
    public function commande(EntityManagerInterface $entityManager, Livres $livre): Response
    {   
        // Vous pouvez ajouter ici la logique pour ajouter le livre à la commande de l'utilisateur.
        // Par exemple :
        // $user = $this->getUser();
        // $user->addToCart($livre);
        // $entityManager->flush();

        // Pour cet exemple, nous redirigeons simplement vers la page de commande.
        return $this->redirectToRoute('app_commande');
    }

    #[Route('/User/livre/show/{id}', name: 'User_livre_show')]
    public function show(Livres $livre): Response
    {  
        return $this->render('livres/show2.html.twig', [
            'livre' => $livre,
        ]);
    }
}

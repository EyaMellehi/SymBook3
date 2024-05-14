<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Orders;
use App\Entity\Commande;
use App\Entity\OrdersDetails;
use App\Repository\LivresRepository;

class OrdersController extends AbstractController
{
    #[Route('/orders/add', name: 'orders_add')]
    public function add(SessionInterface $s,LivresRepository $rep): Response
    {  
         $this->denyAccessUnlessGranted('ROLE_USER');
        $panier=$s->get('panier',[]);
        if($panier===[]){
            $this->addFlash('message','votre panier est vide');
            return $this->redirectToRoute('User_livres');
        }
        $commande=new Commande();
        $commande->setIdClient($this->getUser());
        $commande->setNum(uniqid());
        foreach($panier as $item=> $quantite){
            $orders=new OrdersDetails();
            $livre=$rep->find($item);
            $prix=$livre->getPrix();
            $orders->setLivres($livre);
            $orders->setPrix($prix);
            $orders->setQuantite($quantite);

 
        }

         return $this->render('orders/index.html.twig', [
            'controller_name' => 'OrdersController',
        ]);
    }
}

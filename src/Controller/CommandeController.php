<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livres;
use App\Repository\LivresRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandeController extends AbstractController
{   
    #[Route('/commande', name: 'app_commande')]
    public function index(SessionInterface $s,LivresRepository $livres): Response
    {   
       $panier=$s->get('panier',[]);
        $data=[];
        $total=0;
        foreach($panier as $id=>$qte){
            $livre=$livres->find($id);
            $data[]=[
                'livre'=>$livre,
                'quantite'=>$qte
            ];
            $total += $livre->getPrix()*$qte;
        }
        return $this->render('commande/index.html.twig', compact('data','total'));
    }
    #[Route('/User/livres/commande/{id}', name: 'User_livres_commande')]
    public function add(SessionInterface $s,EntityManagerInterface $entityManager, Livres $livre)
    {   $id=$livre->getId();
        $panier=$s->get('panier',[]);
        if(empty($panier[$id])){
            $panier[$id]=1;
        }else{
            $panier[$id]++;
        }
        $s->set('panier',$panier);
        return $this->redirectToRoute('User_livres');
    }

    #[Route('/User/remove/{id}', name: 'User_livres_remove')]
    public function remove(SessionInterface $s,EntityManagerInterface $entityManager, Livres $livre)
    {   $id=$livre->getId();
        $panier=$s->get('panier',[]);
        if(!empty($panier[$id])){
            if($panier[$id]>1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }
        
        $s->set('panier',$panier);
        return $this->redirectToRoute('app_commande');
    }
    #[Route('/User/delete/{id}', name: 'User_livres_delete')]
    public function delete(SessionInterface $s,EntityManagerInterface $entityManager, Livres $livre)
    {   
        $id=$livre->getId();
        $panier=$s->get('panier',[]);
        if(!empty($panier[$id])){
                unset($panier[$id]);
        }
        
        $s->set('panier',$panier);
        return $this->redirectToRoute('app_commande');
    }

    #[Route('/User/empty', name: 'User_empty')]
    public function empty(SessionInterface $s)
    {   
        $s->remove('panier');
        return $this->redirectToRoute('app_commande');
    }
} 
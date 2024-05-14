<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\LivreType;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivresController extends AbstractController
{
    #[Route('/Admin/livres', name: 'admin_livres')]
    public function index(LivresRepository $rep): Response
    {
        $livres = $rep->findAll();
        //$livres = $rep->findGreaterThan(100);
        //dd($livres);
        return $this->render('livres/index.html.twig', [
            'livres' => $livres,
        ]);
    }
    #[Route('/User/livres', name: 'User_livres')]
    public function index2(LivresRepository $rep): Response
    {
        $livres = $rep->findAll();
        
        return $this->render('livres/index2.html.twig', [
            'livres' => $livres,
        ]);
    }
    #[Route('/admin/livres/show/{id}', name: 'admin_livres_show')]
    public function show(Livres $livre): Response
    {  
        return $this->render('livres/show.html.twig', [
            'livre' => $livre,
        ]);
    }
    #[Route('/User/livres/show/{id}', name: 'user_livres_show')]
    public function show2(Livres $livre): Response
    {  
        return $this->render('livres/showU.html.twig', [
            'livre' => $livre,
        ]);
    }
    
    /*#[Route('/admin/livres/create', name: 'admin_livres_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $livre = new Livres();
        $livre->setImage('https://picsum.photos/300')
            ->setTitre('Titre du livre 10')
            ->setEditeur('Editeur 1')
            ->setISBN('111.1111.1111.1235')
            ->setPrix(200)
            ->setEditedAt(new \DateTimeImmutable('01-01-2024'))
            ->setSlug('titre-du-livre-10')
            ->setResume('hfjhgdkfhfklgfdlkjgjgfmjgfgfjgjgbkjbfl,gj');
        $em->persist($livre);
        $em->flush();
        //dd($livre);
        //return $this->render('livres/create.html.twig', [
        //   'livre' => $livre,
        // ]);
        return $this->redirectToRoute('admin_livres');
    }*/
    #[Route('/Admin/livres/delete/{id}', name: 'admin_livres_delete')]
    public function delete(EntityManagerInterface $em, Livres $livre): Response
    {    //recherche du livre à supprimer

        //suppression du livre trouvé
        $em->remove($livre);
        $em->flush();
        //dd($livre);

        return $this->redirectToRoute('admin_livres');
    }
    
    #[Route('admin/livre/update/{id}', name: 'admin_livre_update')]
    public function update(Livres $livre, EntityManagerInterface $em, Request $request): Response
    {  
        $form = $this->createForm(LivreType::class, $livre);
        //traitement des données
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($livre);
            $em->flush();
            return $this->redirectToRoute('admin_livres');
        }


        return $this->render('livres/update.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('admin/livres/add', name: 'admin_livres_add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {  // Affichage du l'objet formulaire
        $livre = new Livres;
        $form = $this->createForm(LivreType::class, $livre);
        //traitement des données
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($livre);
            $em->flush();
            return $this->redirectToRoute('admin_livres');
        }


        return $this->render('livres/add.html.twig', [
            'f' => $form,
        ]);
    }
}

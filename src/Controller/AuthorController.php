<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AuthorType;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(AuthorRepository $authorRepo): Response
    {
        $authors= $authorRepo->findAll();
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }
    #[Route('/add-author', name: 'app_add_author')]
    public function addAuthor(Request $request, ManagerRegistry $doctrine): Response
    {
        $author = new Author();

        // Créer le formulaire
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, persister et enregistrer
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_author');
        }

        return $this->render('author/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

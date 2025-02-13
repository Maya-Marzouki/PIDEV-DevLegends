<?php

namespace App\Controller;

use App\Entity\ArticlesConseils;
use App\Form\ArticlesConseilsType;
use App\Repository\ArticlesConseilsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class ArticlesConseilsController extends AbstractController
{
    #[Route('/articles-conseils', name: 'app_articles_conseils_index')]
    public function index(ArticlesConseilsRepository $articlesConseilsRepository): Response
    {
        // Affiche tous les articles conseils
        return $this->render('articles_conseils/listArticle.html.twig', [
            'articlesConseils' => $articlesConseilsRepository->findAll(),
        ]);
    }

    #[Route('/articles-conseils/new', name: 'app_articles_conseils_new')]
    public function new(Request $request, ArticlesConseilsRepository $articlesConseilsRepository): Response
    {
        // Crée un nouvel article conseil
        $articleConseil = new ArticlesConseils();
        $form = $this->createForm(ArticlesConseilsType::class, $articleConseil);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('image')->getData(); // Assurez-vous que le champ "image" existe dans le formulaire
    
            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                
                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                        $newFilename
                    );
                    $articleConseil->setImage('assets/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }
    
            $articlesConseilsRepository->save($articleConseil, true);
            $this->addFlash('success', 'Article ajouté avec succès !');
            
            return $this->redirectToRoute('app_articles_conseils_index');
        }
    
        return $this->render('articles_conseils/addArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/articles-conseils/{id}', name: 'app_articles_conseils_show')]
    // public function show(ArticlesConseils $articleConseil): Response
    // {
    //     // Affiche un seul article conseil
    //     return $this->render('articles_conseils/ShowArticle.html.twig', [
    //         'articleConseil' => $articleConseil,
    //     ]);
    // }

    #[Route('/articles-conseils/{id}', name: 'app_articles_conseils_show')]
public function show(ArticlesConseilsRepository $articlesConseilsRepository, int $id): Response
{
    $articleConseil = $articlesConseilsRepository->find($id);

    if (!$articleConseil) {
        $this->addFlash('error', 'Article non trouvé.');
        return $this->redirectToRoute('app_articles_conseils_index');
    }

    return $this->render('articles_conseils/ShowArticle.html.twig', [
        'articleConseil' => $articleConseil,
    ]);
}


    #[Route('/articles-conseils/{id}/edit', name: 'app_articles_conseils_edit')]
    public function edit(Request $request, ArticlesConseils $articleConseil, ArticlesConseilsRepository $articlesConseilsRepository): Response
    {
        // Modifie un article conseil existant
        $form = $this->createForm(ArticlesConseilsType::class, $articleConseil);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('image')->getData();
            
            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                
                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                        $newFilename
                    );
                    $articleConseil->setImage('assets/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }
            $articlesConseilsRepository->save($articleConseil, true);
            $this->addFlash('success', 'Article modifié avec succès !');
            // Redirige vers la page d'index après l'édition
            return $this->redirectToRoute('app_articles_conseils_admin');
        }

        return $this->render('articles_conseils/editArticle.html.twig', [
            'form' => $form->createView(),
            'articleConseil' => $articleConseil,
        ]);
    }

    #[Route('/articles-conseils/{id}/delete', name: 'app_articles_conseils_delete', methods: ['POST'])]
    public function delete(Request $request, ArticlesConseils $articleConseil, ArticlesConseilsRepository $articlesConseilsRepository): Response
    {
        // Supprime un article conseil
        if ($this->isCsrfTokenValid('delete' . $articleConseil->getId(), $request->request->get('_token'))) {
            $articlesConseilsRepository->remove($articleConseil, true);
            $this->addFlash('success', 'L\'article a été supprimé avec succès.');
        }else {
            $this->addFlash('error', 'Échec de la suppression : token CSRF invalide.');
        }

        // Redirige vers la page d'index après la suppression
        return $this->redirectToRoute('app_articles_conseils_index');
    }

    // Redirection vers la Vue d’Admin
    #[Route('/admin/articles-conseils', name: 'app_articles_conseils_admin')]
public function adminIndex(ArticlesConseilsRepository $articlesConseilsRepository): Response
{
    // Affiche la liste des articles pour l'admin
    return $this->render('articles_conseils/viewBackArticle.html.twig', [
        'articlesConseils' => $articlesConseilsRepository->findAll(),
    ]);
}

}

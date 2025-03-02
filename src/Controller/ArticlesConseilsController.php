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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



class ArticlesConseilsController extends AbstractController
{
    #[Route('/articles-conseils', name: 'app_articles_conseils_index')]
    public function index(ArticlesConseilsRepository $articlesConseilsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('search');
        $sortBy = $request->query->get('sortBy', 'titreArticle');
        $sortOrder = $request->query->get('sortOrder', 'ASC');

        $query = $articlesConseilsRepository->searchAndSort($searchTerm, $sortBy, $sortOrder);

        $articleConseil = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        if ($request->isXmlHttpRequest()) {
            return $this->render('articles_conseils/_list.html.twig', [
                'articlesConseils' => $articleConseil,
            ]);
        }

        return $this->render('articles_conseils/listArticle.html.twig', [
            'articlesConseils' => $articleConseil,
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

    #[Route('/articles-conseils/{id}', name: 'app_articles_conseils_show')]
    public function show(ArticlesConseils $articleConseil): Response
    {
        // Affiche un seul article conseil
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
                    // Supprimer l'ancienne image si elle existe
                    if ($articleConseil->getImage() && file_exists($this->getParameter('kernel.project_dir') . '/public/' . $articleConseil->getImage())) {
                        unlink($this->getParameter('kernel.project_dir') . '/public/' . $articleConseil->getImage());
                    }
                    
                    // Mettre à jour l'image
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
public function adminIndex(Request $request, ArticlesConseilsRepository $articlesConseilsRepository): Response
{
    $titre = $request->query->get('titre');
    $categorie = $request->query->get('categorie');

    $articlesConseils = $articlesConseilsRepository->searchArticles($titre, $categorie);

    return $this->render('articles_conseils/viewBackArticle.html.twig', [
        'articlesConseils' => $articlesConseils,
    ]);
}

//     #[Route('/admin/articles-conseils', name: 'app_articles_conseils_admin')]
// public function adminIndex(ArticlesConseilsRepository $articlesConseilsRepository): Response
// {
//     $articlesConseils = $articlesConseilsRepository->findAllOrderedByTitre();

//     return $this->render('articles_conseils/viewBackArticle.html.twig', [
//         'articlesConseils' => $articlesConseils,
//     ]);
// }

#[Route('/articles-conseils/search', name: 'app_articles_conseils_search', methods: ['GET'])]
public function search(Request $request, ArticlesConseilsRepository $articlesConseilsRepository): JsonResponse
{
    $criteria = [
        'titre' => $request->query->get('titre'),
        'categorie' => $request->query->get('categorie'),
        'order' => $request->query->get('order'),
    ];

    $articlesConseils = $articlesConseilsRepository->findByCriteria($criteria);

    $data = [];
    foreach ($articlesConseils as $article) {
        $data[] = [
            'id' => $article->getId(),
            'titreArticle' => $article->getTitreArticle(),
            'contenuArticle' => substr($article->getContenuArticle(), 0, 100) . '...',
            'categorieMentalArticle' => $article->getCategorieMentalArticle(),
            'image' => $article->getImage() ? $this->getParameter('kernel.project_dir') . '/public' . $article->getImage() : null,
        ];
    }

    return new JsonResponse($data);
}


}

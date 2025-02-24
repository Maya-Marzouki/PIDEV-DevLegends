<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    
    #[Route('/commandeclient', name: 'commandeclient')]
    public function showcommandeclient(CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->findAll(); // Devrait être find($id) si on attend un seul ID

    if (!$commande) {
        throw $this->createNotFoundException('Commande non trouvée.');
    }

    return $this->render('commande/showclientcommande.html.twig', [
        'commande' => $commande,
    ]);
    }
    #[Route('/addcommande', name: 'valider_commande', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        
        if (!$commande->getDateCommande()) {
            $commande->setDateCommande(new \DateTime());
        }
        
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        $totalCommande = 0;
        foreach ($commande->getProduits() as $produit) {
            $totalCommande += $produit->getPrixProduit() * $produit->getQteProduit();
        }
    
        // Assigner le total à la commande
        $commande->setTotalCom($totalCommande);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $modePaiement = $form->get('modePaiement')->getData();
            $numeroCarte = $form->get('numeroCarte')->getData();
            $numeroVirement = $form->get('numeroVirement')->getData();
            $paypalEmail = $form->get('paypalEmail')->getData();
        
            if ($modePaiement === 'carte') {
                // Traitement paiement par carte
                // Par exemple, enregistrer le numéro temporairement ou valider la carte
            } elseif ($modePaiement === 'virement') {
                // Traitement paiement par virement
            } elseif ($modePaiement === 'paypal') {
                // Traitement paiement PayPal
            }
            $pays = $form->get('pays')->getData();
            $numTelephone = $form->get('NumTelephone')->getData();

            // Assigner les valeurs des nouveaux champs à la commande
            $commande->setPays($pays);
            $commande->setNumTelephone($numTelephone);
            // Persister la commande
            $entityManager->persist($commande);
            $entityManager->flush();
    
            $this->addFlash('success', 'Commande validée avec le mode de paiement : ' . $modePaiement);
    
            return $this->redirectToRoute('app_commande', ['id' => $commande->getId()]);
            
        }else {
            // Si le formulaire n'est pas valide, affiche les erreurs dans la console
            dump($form->getErrors(true));  // Debugging des erreurs de formulaire
        }
    
        return $this->render('commande/formaddcommande.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/commandes', name: 'app_commande', methods: ['GET'])]
    public function showAll(CommandeRepository $commandeRepository): Response
{
    $commandes = $commandeRepository->findAll();
    
    return $this->render('commande/formshowcommande.html.twig', [
        'commandes' => $commandes, // Note bien 'commandes' au pluriel
    ]);
}
    #[Route('/commande/edit', name: 'editCommande', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_commande');
        }
    
        return $this->render('commande/formeditcommande.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }
    
  


    
#[Route('/commande/delete', name: 'deleteCommande', methods: ['POST'])]
public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
        $entityManager->remove($commande);
        $entityManager->flush();

        $this->addFlash('success', 'Commande supprimée avec succès.');
    }

    return $this->redirectToRoute('app_commande');
}


    //  #[Route('/commande/client', name: 'commandeclient')]
    // public function commandeClient(CommandeRepository $commandeRepository): Response
    // {
    //     // Vous pouvez afficher ici les commandes spécifiques d'un client
    //     return $this->render('commande/showclientcommande.html.twig', [
    //         'commandes' => $commandeRepository->findAll(),  // Ajustez selon votre logique
    //     ]);
    // }

   

    
}

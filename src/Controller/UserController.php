<?php
namespace App\Controller;

use App\Form\UserFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/userList', name: 'userList')]
    public function userList(ManagerRegistry $mr): Response
    {
        $manager = $mr->getManager();
        $repo = $manager->getRepository(User::class);
        $list = $repo->findAll();
        return $this->render('user/userlist.html.twig', [
            'userList' => $list,
        ]);
    }

    #[Route('/addUser', name: 'insertUser')]
    public function insertUser(Request $request, ManagerRegistry $mr): Response
    {
        $manager=$mr->getManager(); 
        $user=new \App\Entity\User();
        $form=$this->createForm(UserFormType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("insertUser");
        }
        return $this->render("user/formadduser.html.twig", ["form" => $form]);
    }  
}
?>
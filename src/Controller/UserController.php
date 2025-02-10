<?php
namespace App\Controller;

use App\Form\UserFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Entity\User;
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
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("insertUser");
        }
        return $this->render("user/formadduser.html.twig", ["form" => $form]);
    }  
    
    #[Route('/deleteUser/{id}', name: 'deleteUser')]
    public function deleteUser(ManagerRegistry $mr, UserRepository $repo, $id): Response
    {
        $manager = $mr->getManager();
        $user = $repo->find($id);
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute("userList");
    }

    #[Route('/updateAdmin/{id}', name: 'updateAdmin')]
    public function updateAdmin(Request $request, ManagerRegistry $mr, $id, UserRepository $repo): Response
    {
        $manager = $mr->getManager();
        $user = new \App\Entity\User();
        $user=$repo->find($id);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('adminProfile');
        }
        return $this->render("user/updateAdmin.html.twig", [
            "form" => $form
        ]);
    }

    /*#[Route('/updateAdmin/{id}', name: 'updateAdmin')]
    public function editAdmin(Request $request, User $user, ManagerRegistry $mr): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $mr->getManager();
            $manager->flush();

            return $this->redirectToRoute('adminProfile');
        }

        return $this->render('user/updateAdmin.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }*/

    #[Route('/updateUser/{id}', name: 'updateUser')]
    public function updateUser(Request $request, ManagerRegistry $mr,$id, UserRepository $repo): Response
    {
        $manager = $mr->getManager();
        $user = new User();
        $user=$repo->find($id);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("userProfile");
        }
        return $this->render("user/formupdateUser.html.twig", [
            'user' => $user,
            "form" => $form
        ]);
    }

    #[Route('/userProfile/{id}', name: 'userProfile')]
    public function showUserProfile($id, UserRepository $repo): Response
    {
        $user = $repo->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('user/userProfile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/adminProfile', name: 'adminProfile')]
    public function showAdminProfile(UserRepository $repo): Response
    {
        $admin = $repo->find(1);
        if (!$admin) {
            throw $this->createNotFoundException('Admin not found');
        }
        return $this->render('user/adminProfile.html.twig', [
            'user' => $admin
        ]);
    }
}
?>
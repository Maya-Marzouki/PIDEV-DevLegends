<?php
namespace App\Controller;

use App\Form\UserFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\AppAuthenticator;

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
            $photoFile = $form->get('userPicture')->getData();
            if ($photoFile) {
            $newFilename = uniqid() . '.' . $photoFile->guessExtension();
            try {
                $photoFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                    $newFilename
                );
                $user->setUserPicture('assets/images/' . $newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', "Erreur lors du chargement de l'image.");
            }
        }
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("insertUser");
        }
        return $this->render("user/formadduser.html.twig", [
            //'user' => $user,
            "form" => $form
        ]);
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
        $originalRole = $user->getUserRole();
        $originalSpec = $user->getDocSpecialty();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('userPicture')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                        $newFilename
                    );
                    $user->setUserPicture('assets/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors du chargement de l'image.");
                }
            }
            $user->setUserRole($originalRole);
            if($user->getUserRole()=="Patient")
                $user->setDocSpecialty($originalSpec);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('adminProfile');
        }
        return $this->render("user/updateAdmin.html.twig", [
            'user' => $user,
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
        $originalRole = $user->getUserRole();
        $originalSpec = $user->getDocSpecialty();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('userPicture')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/assets/images/',
                        $newFilename
                    );
                    $user->setUserPicture('assets/images/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors du chargement de l'image.");
                }
            }
            $user->setUserRole($originalRole);
            if($user->getUserRole()=="Patient")
                $user->setDocSpecialty($originalSpec);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('userProfile', ['id' => $user->getId()]);
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
            throw $this->createNotFoundException('Admin introuvable.');
        }
        return $this->render('user/adminProfile.html.twig', [
            'user' => $admin
        ]);
    }

    /*#[Route('/login', name: 'login')]
public function login(Request $request, EntityManagerInterface $em, SessionInterface $session): Response 
{
    $error = null; // Initialiser la variable $error à null

    if ($request->isMethod('POST')) {
        $userEmail = $request->request->get('userEmail');
        $password = $request->request->get('password');

        // Vérifier l'utilisateur dans la base de données
        $conn = $em->getConnection();
        $sql = "SELECT * FROM user WHERE user_email = :userEmail";
        $stmt = $conn->prepare($sql);
        $userData = $stmt->executeQuery(['userEmail' => $userEmail])->fetchAssociative();

        if ($userData && password_verify($password, $userData['password'])) {
            $session->set('user', $userData);
            return $this->redirectToRoute('userProfile', ['id' => $userData['id']]);
        } else {
            $error = 'Email ou mot de passe incorrect'; // Définir l'erreur
        }
    }

    // Toujours passer la variable $error au template
    return $this->render('user/login.html.twig', [
        'error' => $error,
    ]);
}*/
    /*#[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get authentication errors (if any)
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUseremail = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_userEmail' => $lastUseremail,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony will handle this route automatically.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key in security.yaml.');
    }*/

}
?>
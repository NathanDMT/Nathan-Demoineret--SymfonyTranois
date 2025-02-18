<?php
namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, GalleryRepository $galleryRepo, UserRepository $userRepo, AuthenticationUtils $authenticationUtils, EntityManagerInterface $em): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $email = (string) ($lastUsername ?? '');

        if ($request->isMethod('POST')) {
            $email = (string) $request->request->get('_email', '');
            $password = (string) $request->request->get('_password', '');

            if (!empty($email) && !empty($password)) {
                $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

                if ($user) {
                    if (password_verify($password, $user->getPassword())) {
                        if ($user->getIsBlocked()) {
                            $this->addFlash('error', 'Votre compte est bloqué.');
                        } else {
                            $user->setFailedAttempts(0);
                            $em->flush();
                            $this->addFlash('success', 'Connexion réussie !');
                            $this->get('session')->set('user_id', $user->getId());
                            return $this->redirectToRoute('app_home');
                        }
                    } else {
                        $user->setFailedAttempts($user->getFailedAttempts() + 1);
                        if ($user->getFailedAttempts() >= 3) {
                            $user->setIsBlocked(true);
                            $this->addFlash('error', 'Votre compte a été bloqué après plusieurs tentatives échouées.');
                        } else {
                            $this->addFlash('error', 'Email ou mot de passe incorrect.');
                        }
                        $em->flush();
                    }
                } else {
                    $this->addFlash('error', 'Email ou mot de passe incorrect.');
                }
            } else {
                $this->addFlash('error', 'Email et mot de passe doivent être remplis.');
            }
        }

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('login.html.twig', [
            'last_username' => $email,
            'error' => $error,
        ]);
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(): void { }
}
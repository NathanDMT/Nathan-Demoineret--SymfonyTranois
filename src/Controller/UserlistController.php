<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserlistController extends AbstractController
{
    #[Route('/userlist', name: 'app_user_list')]
    public function listUsers(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if (!$session->has('user')) {
            return $this->redirectToRoute('app_home');
        }
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('', [
            'users' => $users,
        ]);
    }
}
<?php

namespace App\Controller;

use App\Service\PHPMailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpClient\HttpClient;

class RegisterController extends AbstractController
{

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        PHPMailerService $mailerService,
        UserPasswordHasherInterface $passwordHasher
    ): Response {

        if ($request->isMethod('POST')) {
            $email = $request->request->get('registerEmail');
            $username = $request->request->get('registerUsername');
            $age = $request->request->get('registerAge');

            // CAPTCHA PRESENT MAIS INIVISIBLE LORSQUE LE CODE EST ACTIVE, DETECTION DU CAPTCHA FONCTIONNEL

            // $hcaptchaResponse = $request->request->get('h-captcha-response');

            // $client = HttpClient::create();
            // $response = $client->request('POST', 'https://hcaptcha.com/siteverify', [
            //     'body' => [
            //         'secret' => $_ENV['RECAPTCHA_SECRET_KEY'],
            //         'response' => $hcaptchaResponse,
            //     ]
            // ]);

            // $data = $response->toArray();

            // if (!$data['success']) {
            //     $this->addFlash('error', 'La vérification hCaptcha a échoué.');
            //     return $this->redirectToRoute('app_register');
            // }

            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'Un utilisateur avec cet email existe déjà.');
                return $this->redirectToRoute('app_register');
            }

            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setAge($age);
            $user->setIsBlocked(false);
            $user->setCreationDate(new \DateTime());

            $password = bin2hex(random_bytes(12));
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $subject = "Votre inscription est valide";
            $message = "<p>Bonjour $username!</p>
                        <p>Votre compte a été créé avec succès sur notre site de galerie photo.</p>
                        <p>Votre mot de passe est : <strong>$password</strong></p>";

            $mailerService->sendEmail($email, $subject, $message);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('register.html.twig');
    }
}

<?php
namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Repository\PhotoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(GalleryRepository $galleryRepo, PhotoRepository $photoRepo, UserRepository $userRepo): Response
    {
        $galleryWithPhotos = $galleryRepo->findBy(['isPublished' => true]);
        $randomGallery = null;
        if (!empty($galleryWithPhotos)) {
            $randomGallery = $galleryWithPhotos[array_rand($galleryWithPhotos)];
        }
        $galleries = $galleryRepo->findAll();

        return $this->render('home.html.twig', [
            'randomGallery' => $randomGallery,
            'galleries' => $galleries,
        ]);
    }
}

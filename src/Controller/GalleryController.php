<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Photo;
use App\Repository\GalleryRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gallery')]
class GalleryController extends AbstractController
{
    #[Route('/', name: 'app_gallery')]
    public function index(PhotoRepository $photoRepository, GalleryRepository $galleryRepository): Response
    {
        $user = $this->getUser();
        $galleries = $galleryRepository->findBy(['user' => $user]);

        $photos = [];
        foreach ($galleries as $gallery) {
            $photos = array_merge($photos, $photoRepository->findBy(['gallery' => $gallery], ['dateUpload' => 'DESC']));
        }

        return $this->render('gallery/import.html.twig', [
            'photos' => $photos,
        ]);
    }

    #[Route('/upload', name: 'account_upload', methods: ['GET', 'POST'])]
    public function upload(Request $request, EntityManagerInterface $entityManager, GalleryRepository $galleryRepository): Response
    {
        $user = $this->getUser();
        $gallery = $galleryRepository->findOneBy(['user' => $user]);

        if (!$gallery) {
            $this->addFlash('info', 'Vous devez créer une galerie avant d\'importer une photo.');
            return $this->redirectToRoute('gallery_create');
        }

        $file = $request->files->get('photo');
        if (!$file || !$file->isValid()) {
            $this->addFlash('error', 'Fichier non valide.');
            return $this->redirectToRoute('app_gallery');
        }

        $publicationOrder = $request->request->get('publication_order') ?: ($gallery->getPhotos()->count() + 1);

        $fileName = uniqid('', true) . '.' . $file->guessExtension();
        $file->move($this->getParameter('photos_directory'), $fileName);

        $photo = new Photo();
        $photo->setGallery($gallery);
        $photo->setUser($user);
        $photo->setPath('uploads/photos/' . $fileName);
        $photo->setUrl('/uploads/photos/' . $fileName);
        $photo->setFileName($fileName);
        $photo->setFileSize(filesize($this->getParameter('photos_directory') . '/' . $fileName));
        $photo->setDateUpload(new \DateTime());
        $photo->setPublicationOrder($publicationOrder);

        $entityManager->persist($photo);
        $entityManager->flush();

        $this->addFlash('success', 'Photo ajoutée avec l\'utilisateur et gallery_id !');
        return $this->redirectToRoute('app_gallery');
    }

    #[Route('/photo/delete/{id}', name: 'account_delete_photo', methods: ['POST'])]
    public function deletePhoto(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $photo = $entityManager->getRepository(Photo::class)->find($id);

        if (!$photo) {
            $this->addFlash('error', 'Photo introuvable.');
            return $this->redirectToRoute('app_gallery');
        }

        if ($this->isCsrfTokenValid('delete' . $photo->getIdPhoto(), $request->request->get('_token'))) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/' . basename($photo->getPath());

            if (is_file($filePath) && is_writable($filePath)) {
                unlink($filePath);
            } else {
                $this->addFlash('error', 'Fichier introuvable ou non supprimable.');
            }

            $entityManager->remove($photo);
            $entityManager->flush();

            $this->addFlash('success', 'Photo supprimée avec succès !');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }
        return $this->redirectToRoute('app_gallery');
    }

    #[Route('/gallery/create', name: 'gallery_create')]
    public function createGallery(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gallery = new Gallery();
        $form = $this->createFormBuilder($gallery)
            ->add('galleryName')
            ->add('description')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gallery->setUser($this->getUser());
            $gallery->setIsPublished(0);
            $entityManager->persist($gallery);
            $entityManager->flush();

            $this->addFlash('success', 'Galerie créée avec succès ! Vous pouvez maintenant importer des photos.');
            return $this->redirectToRoute('account_upload');
        }

        return $this->render('gallery/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

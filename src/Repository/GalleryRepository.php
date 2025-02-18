<?php
namespace App\Repository;

use App\Entity\Gallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Gallery::class);
    }

    public function findUsersWithPublishedPhotos() {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.user', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult();
    }

    public function findRandomGallery() {
        $galleries = $this->createQueryBuilder('g')
            ->innerJoin('g.photos', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();

        if (empty($galleries)) {
            return null;
        }

        return $galleries[array_rand($galleries)];
    }
}

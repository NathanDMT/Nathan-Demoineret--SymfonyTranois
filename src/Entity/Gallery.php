<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
class Gallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $idGallery;

    #[ORM\Column(type: 'string', length: 255)]
    private string $galleryName;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'boolean')]
    private $isPublished = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'galleries')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'gallery', cascade: ['persist', 'remove'])]
    private Collection $photos;

    public function __construct() {
        $this->photos = new ArrayCollection();
    }

    public function getPhotos(): Collection {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setGallery($this);
        }
        return $this;
    }

    public function removePhoto(Photo $photo): self {
        if ($this->photos->removeElement($photo)) {
            if ($photo->getGallery() === $this) {
                $photo->setGallery(null);
            }
        }
        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;
        return $this;
    }

    public function getIdGallery(): ?int {
        return $this->idGallery;
    }

    public function getUserId(): ?string {
        return $this->userId;
    }

    public function getGalleryName(): ?string {
        return $this->galleryName;
    }

    public function setGalleryName(string $galleryName): static {
        $this->galleryName = $galleryName;
        return $this;
    }

    public function isPublished(): bool {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): void {
        $this->isPublished = $isPublished;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): static {
        $this->description = $description;
        return $this;
    }
}

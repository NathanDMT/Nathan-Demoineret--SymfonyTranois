<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idPhoto = null;

    #[ORM\Column(name: 'file_name', type: 'string', length: 255, nullable: false)]
    private string $fileName;

    #[ORM\Column(name: 'url', type: 'string', length: 255, nullable: false)]
    private string $url;

    #[ORM\Column(name: 'date_upload', type: 'datetime', nullable: false)]
    private \DateTime $dateUpload;

    #[ORM\Column(name: 'file_size', type: 'integer')]
    private int $fileSize;

    #[ORM\Column(name: 'publication_order', type: 'integer', nullable: true, options: ['default' => null])]
    private int $publicationOrder;

    #[ORM\Column(type: 'string', length: 255)]
    private string $path;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Gallery::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(name: 'gallery_id', referencedColumnName: 'id_gallery', nullable: true, onDelete: 'CASCADE')]
    private ?Gallery $gallery = null;


    public function getPath(): string {
        return $this->path;
    }

    public function setPath(string $path): self {
        $this->path = $path;
        return $this;
    }

    public function getGallery(): ?Gallery {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self {
        $this->gallery = $gallery;
        return $this;
    }

    public function getIdPhoto(): ?int {
        return $this->idPhoto;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(string $url): self {
        $this->url = $url;
        return $this;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): static {
        $this->id = $id;
        return $this;
    }

    public function getFileName(): ?string {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self {
        $this->fileName = $fileName;
        return $this;
    }

    public function getFilePath(): ?string {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static {
        $this->filePath = $filePath;
        return $this;
    }

    public function getDateUpload(): ?\DateTime {
        return $this->dateUpload;
    }

    public function setDateUpload(\DateTime $dateUpload): static {
        $this->dateUpload = $dateUpload;
        return $this;
    }

    public function getFileSize(): ?int {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): self {
        $this->fileSize = $fileSize;
        return $this;
    }

    public function getPublicationOrder(): ?string {
        return $this->publicationOrder;
    }

    public function setPublicationOrder(string $publicationOrder): static {
        $this->publicationOrder = $publicationOrder;
        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(User $user): self {
        $this->user = $user;
        return $this;
    }
}

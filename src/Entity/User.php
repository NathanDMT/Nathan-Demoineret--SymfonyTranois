<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(type: "integer")]
    private ?int $age = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'integer')]
    private $isBlocked = 0;

    #[ORM\Column]
    private ?bool $isAdmin = false;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'integer')]
    private int $failedAttempts = 0;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\OneToMany(targetEntity: Gallery::class, mappedBy: 'user')]
    private Collection $galleries;

    public function __construct() {
        $this->galleries = new ArrayCollection();
    }

    public function getGalleries(): Collection {
        return $this->galleries;
    }

    public function getId(): ?int {
        return $this->id;
    }


    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername(?string $username): void {
        $this->username = $username;
    }

    public function getAge(): ?int {
        return $this->age;
    }

    public function setAge(?int $age): void {
        $this->age = $age;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(?string $password): void {
        $this->password = $password;
    }

    public function getIsBlocked(): bool {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): void {
        $this->isBlocked = $isBlocked;
    }

    public function getIsAdmin(): ?bool {
        return $this->isAdmin;
    }

    public function setIsAdmin(?bool $isAdmin): void {
        $this->isAdmin = $isAdmin;
    }

    public function getCreationDate(): ?\DateTimeInterface {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): void {
        $this->creationDate = $creationDate;
    }

    public function getRoles(): array {
        return $this->isAdmin ? ['ROLE_ADMIN'] : ['ROLE_USER'];
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void { }

    public function getUserIdentifier(): string {
        return $this->email;
    }

    public function getFailedAttempts(): int {
        return $this->failedAttempts;
    }

    public function setFailedAttempts(int $failedAttempts): self {
        $this->failedAttempts = $failedAttempts;
        return $this;
    }
}

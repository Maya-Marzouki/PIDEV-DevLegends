<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;


#[ORM\Entity(repositoryClass: ProfilRepository::class)]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'profil', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le prénom est requis")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le prénom doit contenir au plus {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your first name cannot contain a number',
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le nom est requis")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom doit contenir au plus {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Le nom ne peut pas contenir des caractères numériques',
    )]
    private ?string $lastName = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"L'age est requis")]
    #[Assert\Range(
        min: 3,
        max: 100,
        notInRangeMessage: 'Vous devez avoir entre {{ min }} ans et {{ max }} ans pour vous inscrire',
    )]
    private ?int $userAge = null;

    #[ORM\Column(length: 20)]
    private ?string $userRole = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userPicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $docSpecialty = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function setUserRole(?string $userRole): static
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getUserAge(): ?int
    {
        return $this->userAge;
    }

    public function setUserAge(?int $userAge): static
    {
        $this->userAge = $userAge;

        return $this;
    }

    public function getUserPicture(): ?string
    {
        return $this->userPicture;
    }

    public function setUserPicture(?string $userPicture): static
    {
        $this->userPicture = $userPicture;

        return $this;
    }

    public function getDocSpecialty(): ?string
    {
        return $this->docSpecialty;
    }

    public function setDocSpecialty(?string $docSpecialty): static
    {
        $this->docSpecialty = $docSpecialty;

        return $this;
    }
}

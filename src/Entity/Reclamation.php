<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: "Le sujet doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le sujet ne doit pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/",
        message: "Le sujet ne peut contenir que des lettres et des espaces."
    )]
    #[Assert\NotBlank(message: "Le sujet est obligatoire")]
    private ?string $sujetRec = null;

    #[ORM\Column]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "Le message doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le message ne doit pas dépasser {{ limit }} caractères."
    )]
    #[Assert\NotBlank(message: "Le contenu est obligatoire")]
    private ?string $contenuRec = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateRec = null;
    public function __construct()
    {
        $this->dateRec = new \DateTime(); // Définir la date automatiquement
    }

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Donnez votre email")]
    #[Assert\Length(max: 25, maxMessage: "L'email ne doit pas dépasser {{ limit }} caractères.")]
    #[Assert\Email(message: "Cet email {{ value }} n'est pas valide")]
    private ?string $emailDes = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $statutRec = 'Pas traitée';

    #[ORM\OneToOne(mappedBy: 'reclamation', cascade: ['persist', 'remove'])]
    private ?Avis $avis = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?User $user = null;

    public function getStatutRec(): string
    {
        return $this->statutRec;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujetRec(): ?string
    {
        return $this->sujetRec;
    }

    public function setSujetRec(string $sujetRec): static
    {
        $this->sujetRec = $sujetRec;

        return $this;
    }

    public function getContenuRec(): ?string
    {
        return $this->contenuRec;
    }

    public function setContenuRec(string $contenuRec): static
    {
        $this->contenuRec = $contenuRec;

        return $this;
    }

    public function setStatutRec(string $statutRec): static
    {
        $this->statutRec = $statutRec;
        return $this;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->dateRec;
    }

    public function setDateRec(?\DateTimeInterface $dateRec): static
    {
        $this->dateRec = $dateRec;
        return $this;
    }


    public function getEmailDes(): ?string
    {
        return $this->emailDes;
    }

    public function setEmailDes(string $emailDes): static
    {
        $this->emailDes = $emailDes;

        return $this;
    }

    public function getAvis(): ?Avis
    {
        return $this->avis;
    }

    public function setAvis(?Avis $avis): static
    {
        // unset the owning side of the relation if necessary
        if ($avis === null && $this->avis !== null) {
            $this->avis->setReclamation(null);
        }

        // set the owning side of the relation if necessary
        if ($avis !== null && $avis->getReclamation() !== $this) {
            $avis->setReclamation($this);
        }

        $this->avis = $avis;

        return $this;
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
}
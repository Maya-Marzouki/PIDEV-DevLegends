<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
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
    private ?string $sujetAvis = null;

    #[ORM\Column]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "Le message doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le message ne doit pas dépasser {{ limit }} caractères."
    )]
    #[Assert\NotBlank(message: "Le contenu est obligatoire")]
    private ?string $contenuAvis = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Donnez une note sur 10")]
    #[Assert\Positive(message: "La note doit être un nombre positif.")]
    #[Assert\LessThanOrEqual(10, message: "La note ne peut pas dépasser 10.")]
    private ?int $noteAvis = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThanOrEqual("today", message: "La date ne peut pas être dans le futur.")]
    #[Assert\GreaterThanOrEqual("2023-01-01", message: "La date doit être postérieure au 1er janvier 2023.")]
    #[Assert\NotBlank(message: "Donnez une date précise")]
    private ?\DateTimeInterface $dateAvis = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Donnez votre email")]
    #[Assert\Length(max: 25, maxMessage: "L'email ne doit pas dépasser {{ limit }} caractères.")]
    #[Assert\Email(message: "Cet email {{ value }} n'est pas valide")]
    private ?string $emailAvis = null;

    #[ORM\OneToOne(inversedBy: 'avis', cascade: ['persist', 'remove'])]
    private ?Reclamation $reclamation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujetAvis(): ?string
    {
        return $this->sujetAvis;
    }

    public function setSujetAvis(string $sujetAvis): static
    {
        $this->sujetAvis = $sujetAvis;

        return $this;
    }

    public function getContenuAvis(): ?string
    {
        return $this->contenuAvis;
    }

    public function setContenuAvis(string $contenuAvis): static
    {
        $this->contenuAvis = $contenuAvis;

        return $this;
    }

    public function getNoteAvis(): ?int
    {
        return $this->noteAvis;
    }

    public function setNoteAvis(int $noteAvis): static
    {
        $this->noteAvis = $noteAvis;

        return $this;
    }

    public function getDateAvis(): ?\DateTimeInterface
    {
        return $this->dateAvis;
    }

    public function setDateAvis(?\DateTimeInterface $dateAvis): static
    {
        $this->dateAvis = $dateAvis;
        return $this;
    }


    public function getEmailAvis(): ?string
    {
        return $this->emailAvis;
    }

    public function setEmailAvis(string $emailAvis): static
    {
        $this->emailAvis = $emailAvis;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): static
    {
        $this->reclamation = $reclamation;

        return $this;
    }


}

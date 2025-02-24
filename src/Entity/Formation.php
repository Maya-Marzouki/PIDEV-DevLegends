<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de la formation est obligatoire.")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $titreFor = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de la formation est obligatoire.")]
    #[Assert\Type(type: '\DateTimeInterface', message: "Veuillez entrer une date valide.")]
    #[Assert\GreaterThan("today", message: "La date doit être ultérieure à aujourd'hui.")]
    private ?\DateTimeInterface $dateFor = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le lieu de la formation est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le lieu doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le lieu ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $lieuFor = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[Assert\Choice(
        choices: ['Étudiant en médecine', 'Médecin'],
        message: "Le statut doit être 'Étudiant en médecine' ou 'Médecin'."
    )]
    private ?string $statutFor = null;

    // 🔹 Ajout de la relation OneToMany vers Evenement
    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Evenement::class)]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreFor(): ?string
    {
        return $this->titreFor;
    }

    public function setTitreFor(string $titreFor): static
    {
        $this->titreFor = $titreFor;
        return $this;
    }

    public function getDateFor(): ?\DateTimeInterface
    {
        return $this->dateFor;
    }

    public function setDateFor(\DateTimeInterface $dateFor): static
    {
        $this->dateFor = $dateFor;
        return $this;
    }

    public function getLieuFor(): ?string
    {
        return $this->lieuFor;
    }

    public function setLieuFor(string $lieuFor): static
    {
        $this->lieuFor = $lieuFor;
        return $this;
    }

    public function getStatutFor(): ?string
    {
        return $this->statutFor;
    }


    public function setStatutFor(string $statutFor): static
    {
        $this->statutFor = $statutFor;
        return $this;
    }

    // 🔹 Getter pour la relation OneToMany (Evenements associés)
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }
    
}

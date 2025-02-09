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

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le sujet est obligatoire")]
    private ?string $sujetAvis = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire")]
    private ?string $contenuAvis = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Donnez une note sur 10")]
    #[Assert\Range(
        min: 0,
        max: 10,
        notInRangeMessage: "La note doit être comprise entre {{ min }} et {{ max }}."
    )]
    private ?int $noteAvis = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Donnez une date précise")]
    private ?\DateTimeInterface $dateAvis = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Donnez votre email")]
    #[Assert\Email(message: "Cet email {{ value }} n'est pas valide")]
    private ?string $emailAvis = null;

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
}

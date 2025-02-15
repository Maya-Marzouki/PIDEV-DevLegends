<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sujetRec = null;

    #[ORM\Column(length: 255)]
    private ?string $contenuRec = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRec = null;

    #[ORM\Column(length: 255)]
    private ?string $emailDes = null;

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

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->dateRec;
    }

    public function setDateRec(\DateTimeInterface $dateRec): static
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
}

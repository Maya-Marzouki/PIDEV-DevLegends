<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Formation::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    #[ORM\Column(length: 255)]
    private ?string $nomParticipant = null;

    #[ORM\Column(length: 255)]
    private ?string $emailParticipant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateParticipation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;
        return $this;
    }

    public function getNomParticipant(): ?string
    {
        return $this->nomParticipant;
    }

    public function setNomParticipant(string $nomParticipant): static
    {
        $this->nomParticipant = $nomParticipant;
        return $this;
    }

    public function getEmailParticipant(): ?string
    {
        return $this->emailParticipant;
    }

    public function setEmailParticipant(string $emailParticipant): static
    {
        $this->emailParticipant = $emailParticipant;
        return $this;
    }

    public function getDateParticipation(): ?\DateTimeInterface
    {
        return $this->dateParticipation;
    }

    public function setDateParticipation(\DateTimeInterface $dateParticipation): static
    {
        $this->dateParticipation = $dateParticipation;
        return $this;
    }
}

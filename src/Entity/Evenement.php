<?php  

namespace App\Entity;  

use App\Repository\EvenementRepository; 
use Doctrine\DBAL\Types\Types; 
use Doctrine\ORM\Mapping as ORM; 
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)] 
class Evenement 
{     
    #[ORM\Id]     
    #[ORM\GeneratedValue]     
    #[ORM\Column]     
    private ?int $id = null;      

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de l'événement est obligatoire.")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $titreEvent = null;      

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de l'événement est obligatoire.")]
    #[Assert\Type(type: '\DateTimeInterface', message: "Veuillez entrer une date valide.")]
    #[Assert\GreaterThan("today", message: "La date doit être ultérieure à aujourd'hui.")]
    private ?\DateTimeInterface $dateEvent = null;      

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le lieu de l'événement est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le lieu doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le lieu ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $lieuEvent = null;      

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut de l'événement est obligatoire.")]
    #[Assert\Choice(
        choices: ['Étudiant en médecine', 'Médecin'],
        message: "Le statut doit être 'Étudiant en médecine' ou 'Médecin'."
    )]
    private ?string $statutEvent = null;

    // 🔹 Ajout de la relation ManyToOne vers Formation
    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'evenements')]
    #[ORM\JoinColumn(nullable: true)] // NULL autorisé si un événement n'a pas de formation associée
    private ?Formation $formation = null;

    public function getId(): ?int     
    {         
        return $this->id;     
    }      

    public function getTitreEvent(): ?string     
    {         
        return $this->titreEvent;     
    }      

    public function setTitreEvent(string $titreEvent): static     
    {         
        $this->titreEvent = $titreEvent;          
        return $this;     
    }      

    public function getDateEvent(): ?\DateTimeInterface     
    {         
        return $this->dateEvent;     
    }      

    public function setDateEvent(\DateTimeInterface $dateEvent): static     
    {         
        $this->dateEvent = $dateEvent;          
        return $this;     
    }      

    public function getLieuEvent(): ?string     
    {         
        return $this->lieuEvent;     
    }      

    public function setLieuEvent(string $lieuEvent): static     
    {         
        $this->lieuEvent = $lieuEvent;          
        return $this;     
    }      

    public function getStatutEvent(): ?string     
    {         
        return $this->statutEvent;     
    }      

    public function setStatutEvent(string $statutEvent): static     
    {         
        $this->statutEvent = $statutEvent;          
        return $this;     
    }  

    // 🔹 Getters et Setters pour la relation Formation
    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;
        return $this;
    }
}  

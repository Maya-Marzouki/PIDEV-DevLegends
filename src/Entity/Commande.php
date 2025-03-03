<?php

namespace App\Entity;


use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{


   



    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Le nom du client est obligatoire")]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le nom ne peut dépasser 30 caractères"
    )]
    #[Assert\Regex(
        pattern: "/^[^\d]+$/",
        message: "Le nom ne doit pas contenir de chiffres"
    )] private ?string $nomClient = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse email est obligatoire")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide")]
    private ?string $adresseEmail = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de commande est obligatoire")]
    #[Assert\Type(\DateTimeInterface::class, message: "La date de commande doit être une date valide")]
    #[Assert\GreaterThanOrEqual("today", message: "La date de commande ne peut pas être dans le passé.")]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "L'adresse doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'adresse ne peut dépasser {{ limit }} caractères"
    )]    private ?string $adresse = null;
    
    

    #[ORM\Column]
    #[Assert\Positive(message: "Le total de la commande doit être un montant positif")]
    private ?float $totalCom = null;
    /**
     * @var Collection|Produit[]
     */
    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[ORM\JoinTable(name: 'commande_produit')]  // Ceci crée une table d'association
    private $produits;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire")]
    #[Assert\Regex(
    pattern: "/^\d{8}$/",
    message: "Le numéro de téléphone doit contenir exactement 8 chiffres."
)]
    private ?int $NumTelephone = null;
    #[ORM\Column(length: 255, nullable: true)]
private ?string $paymentId = null;



    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;
    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->dateCommande = new \DateTime(); // Définit la date actuelle
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getAdresseEmail(): ?string
    {
        return $this->adresseEmail;
    }

    public function setAdresseEmail(string $adresseEmail): static
    {
        $this->adresseEmail = $adresseEmail;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

   

   

    public function getTotalCom(): ?float
    {
        return $this->totalCom;
    }

    public function setTotalCom(float $totalCom): static
    {
        $this->totalCom = $totalCom;

        return $this;
    }
    public function getPaymentId(): ?string
{
    return $this->paymentId;
}

public function setPaymentId(?string $paymentId): static
{
    $this->paymentId = $paymentId;
    return $this;
}
    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        $this->produits->removeElement($produit);

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getNumTelephone(): ?int
    {
        return $this->NumTelephone;
    }

    public function setNumTelephone(int $NumTelephone): static
    {
        $this->NumTelephone = $NumTelephone;

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
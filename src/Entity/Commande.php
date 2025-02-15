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


    public const STATUT_EN_ATTENTE = 'en attente';
    public const STATUT_EXPEDIE = 'expédié';
    public const STATUT_LIVRE = 'livré';
    public const STATUT_ANNULE = 'annulé';




    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du client est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit contenir au moins 2 caractères",
        maxMessage: "Le nom ne peut dépasser 50 caractères"
    )] private ?string $nomClient = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse email est obligatoire")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide")]
    private ?string $adresseEmail = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de commande est obligatoire")]
    #[Assert\Type(\DateTimeInterface::class, message: "La date de commande doit être une date valide")]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "L'adresse doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'adresse ne peut dépasser {{ limit }} caractères"
    )]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut est obligatoire")]
    #[Assert\Choice(
        choices: [self::STATUT_EN_ATTENTE, self::STATUT_EXPEDIE, self::STATUT_LIVRE, self::STATUT_ANNULE],
        message: "Le statut doit être 'en attente', 'expédié', 'livré' ou 'annulé'"
    )]
    private ?string $statutCom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le total de la commande est obligatoire")]
    #[Assert\Positive(message: "Le total de la commande doit être un montant positif")]
    private ?float $totalCom = null;
    /**
     * @var Collection|Produit[]
     */
    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[ORM\JoinTable(name: 'commande_produit')]  // Ceci crée une table d'association
    private $produits;
    public function __construct()
    {
        $this->produits = new ArrayCollection();
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

    public function getStatutCom(): ?string
    {
        return $this->statutCom;
    }

    public function setStatutCom(string $statutCom): static
    {
        $this->statutCom = $statutCom;

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

}

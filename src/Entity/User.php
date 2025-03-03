<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('userEmail', 'Cet email existe déjà au sein de l\'application.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(length: 60, unique:true)]
    #[Assert\NotBlank(message:"L'adresse email est requis")]
    #[Assert\Email(message:"L'adresse email {{ value }} n'est pas valide")]
    #[Assert\Length(
        max: 60,
        maxMessage: "L'adresse email doit contenir au moins {{ limit }} caractères",
    )]
    private ?string $userEmail = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le mot de passe est requis")]
    #[Assert\Length(
        min: 8,
        minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,20}$/',
        message: 'Le mot de passe est trop faible. Il doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.'
    )]
    private string $password;

    #[Assert\NotBlank(message: "La confirmation du mot de passe est requise.")]
    #[Assert\Expression(
        "this.getPassword() === this.getPlainPassword()",
        message: "Les mots de passe ne correspondent pas."
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 20)]
    //#[Assert\NotBlank(message:"Le role est requis")]
    private ?string $userRole = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"L'age est requis")]
    #[Assert\Range(
        min: 3,
        max: 100,
        notInRangeMessage: 'Vous devez avoir entre {{ min }} ans et {{ max }} ans pour vous inscrire',
    )]
    private ?int $userAge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userPicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $docSpecialty = null;

    #[ORM\Column]
    private ?bool $statutCompte = true;

    /**
     * @var Collection<int, Contrat>
     */
    #[ORM\OneToMany(targetEntity: Contrat::class, mappedBy: 'user')]
    private Collection $contrats;

    #[ORM\Column(length: 11)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est requis.")]
    #[Assert\Regex(
        pattern: '/^\d{11}$/',
        message: "Le numéro de téléphone doit être composé exactement de 11 chiffres.(inclu 216 au début)"
    )]
    private ?string $numTel = null;

    #[ORM\Column(type: "string", length: 6, nullable: true)]
    private ?string $resetCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $discountCode = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isDiscountUsed = false;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Profil $profil = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $bannedUntil = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
    }

    public function getBannedUntil(): ?\DateTimeInterface
    {
        return $this->bannedUntil;
    }

    public function setBannedUntil(?\DateTimeInterface $bannedUntil): self
    {
        $this->bannedUntil = $bannedUntil;
        return $this;
    }

    // Méthode pour vérifier si l'utilisateur est actuellement banni
    public function isBanned(): bool
    {
        return $this->bannedUntil && $this->bannedUntil > new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    public function setResetCode(?string $resetCode): self
    {
        $this->resetCode = $resetCode;
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

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(?string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->userEmail;
    }


    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Contrat>
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): static
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setUser($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): static
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getUser() === $this) {
                $contrat->setUser(null);
            }
        }

        return $this;
    }

    public function isStatutCompte(): ?bool
    {
        return $this->statutCompte;
    }

    public function setStatutCompte(?bool $statutCompte): static
    {
        $this->statutCompte = $statutCompte;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(?string $numTel): static
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }


    public function getDiscountCode(): ?string
{
    return $this->discountCode;
}

public function setDiscountCode(?string $discountCode): self
{
    $this->discountCode = $discountCode;
    return $this;
}

public function isDiscountUsed(): bool
{
    return $this->isDiscountUsed;
}

public function setIsDiscountUsed(bool $isDiscountUsed): self
{
    $this->isDiscountUsed = $isDiscountUsed;
    return $this;
}

public function getProfil(): ?Profil
{
    return $this->profil;
}

public function setProfil(?Profil $profil): static
{
    // unset the owning side of the relation if necessary
    if ($profil === null && $this->profil !== null) {
        $this->profil->setUser(null);
    }

    // set the owning side of the relation if necessary
    if ($profil !== null && $profil->getUser() !== $this) {
        $profil->setUser($this);
    }

    $this->profil = $profil;

    return $this;
}

public function getLatitude(): ?float
{
    return $this->latitude;
}

public function setLatitude(?float $latitude): static
{
    $this->latitude = $latitude;

    return $this;
}

public function getLongitude(): ?float
{
    return $this->longitude;
}

public function setLongitude(?float $longitude): static
{
    $this->longitude = $longitude;

    return $this;
}

public function getAddress(): ?string
{
    return $this->address;
}

public function setAddress(?string $address): static
{
    $this->address = $address;

    return $this;
}
}

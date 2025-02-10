<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "La date de consultation est obligatoire.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date ne peut pas être antérieure à aujourd'hui.")]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTimeInterface $dateCons = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le lien de la visioconsultation ne peut pas être vide.")]
    #[Assert\Url(message: "Veuillez entrer un lien valide.")]
    private ?string $lienVisioCons = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le score mental ne peut pas être vide.")]
    #[Assert\Range(
        min: 0,
        max: 100,
        notInRangeMessage: "Le score mental doit être entre {{ min }} et {{ max }}.",
    )]
    private ?int $scoreMental = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'état mental ne peut pas être vide.")]
    private ?string $etatMental = null;

    #[ORM\Column(length: 255)]    #[Assert\NotBlank(message: "La note de consultation ne peut pas être vide.")]
    private ?string $notesCons = null;

    /**
     * @var Collection<int, Quiz>
     */
    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'consultationQ')]
    private Collection $quizzes;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    private ?string $prenom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "L'âge ne peut pas être vide.")]
    #[Assert\Range(
        min: 0,
        max: 120,
        notInRangeMessage: "L'âge doit être entre {{ min }} et {{ max }}."
    )]
    private ?int $age = null;

    public function __construct()
    {
        $this->quizzes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCons(): ?\DateTimeInterface
    {
        return $this->dateCons;
    }

    public function setDateCons(\DateTimeInterface $dateCons): static
    {
        $this->dateCons = $dateCons;

        return $this;
    }

    public function getLienVisioCons(): ?string
    {
        return $this->lienVisioCons;
    }

    public function setLienVisioCons(?string $lienVisioCons): static
    {
        $this->lienVisioCons = $lienVisioCons;

        return $this;
    }

    public function getScoreMental(): ?int
    {
        return $this->scoreMental;
    }

    public function setScoreMental(?int $scoreMental): static
    {
        $this->scoreMental = $scoreMental;

        return $this;
    }

    public function getEtatMental(): ?string
    {
        return $this->etatMental;
    }

    public function setEtatMental(?string $etatMental): static
    {
        $this->etatMental = $etatMental;

        return $this;
    }

    public function getNotesCons(): ?string
    {
        return $this->notesCons;
    }

    public function setNotesCons(?string $notesCons): static
    {
        $this->notesCons = $notesCons;

        return $this;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): static
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes->add($quiz);
            $quiz->setConsultationQ($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): static
    {
        if ($this->quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getConsultationQ() === $this) {
                $quiz->setConsultationQ(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }
}

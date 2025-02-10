<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    private ?\DateTimeInterface $dateCons = null;

    #[ORM\Column(length: 255)]
    private ?string $lienVisioCons = null;

    #[ORM\Column]
    private ?int $scoreMental = null;

    #[ORM\Column(length: 255)]
    private ?string $etatMental = null;

    #[ORM\Column(length: 255)]
    private ?string $notesCons = null;

    /**
     * @var Collection<int, Quiz>
     */
    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'consultationQ')]
    private Collection $quizzes;

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

    public function setLienVisioCons(string $lienVisioCons): static
    {
        $this->lienVisioCons = $lienVisioCons;

        return $this;
    }

    public function getScoreMental(): ?int
    {
        return $this->scoreMental;
    }

    public function setScoreMental(int $scoreMental): static
    {
        $this->scoreMental = $scoreMental;

        return $this;
    }

    public function getEtatMental(): ?string
    {
        return $this->etatMental;
    }

    public function setEtatMental(string $etatMental): static
    {
        $this->etatMental = $etatMental;

        return $this;
    }

    public function getNotesCons(): ?string
    {
        return $this->notesCons;
    }

    public function setNotesCons(string $notesCons): static
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
}

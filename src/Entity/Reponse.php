<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le texte de la réponse ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        minMessage: "Le texte doit contenir au moins {{ limit }} caractères.",
        max: 255,
        maxMessage: "Le texte ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $answerText = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le score ne peut pas être vide.")]
    #[Assert\Range(
        min: 0,
        max: 10,
        notInRangeMessage: "Le score doit être entre {{ min }} et {{ max }}."
    )]
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Une réponse doit être associée à une question.")]
    private ?Question $question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswerText(): ?string
    {
        return $this->answerText;
    }

    public function setAnswerText(?string $answerText): static
    {
        $this->answerText = $answerText;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }
}

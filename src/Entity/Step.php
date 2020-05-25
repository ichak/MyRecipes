<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StepRepository::class)
 */
class Step
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Ordre;

    /**
     * @ORM\Column(type="text")
     */
    private $Step;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="step")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

    public function __toString()
    {
        return (string) $this->getStep();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdre(): ?int
    {
        return $this->Ordre;
    }

    public function setOrdre(int $Ordre): self
    {
        $this->Ordre = $Ordre;

        return $this;
    }

    public function getStep(): ?string
    {
        return $this->Step;
    }

    public function setStep(string $Step): self
    {
        $this->Step = $Step;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}

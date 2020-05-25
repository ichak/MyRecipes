<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=160)
     * @Assert\Length(
     *      min = 2,
     *      max = 160,
     *      minMessage = "Le nom doit être plus grand que {{ limit }} caractères",
     *      maxMessage = "Le nom doit être plus petit que {{ limit }} caractères",
     *      allowEmptyString = false
     * )
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @ORM\OneToMany(targetEntity=RecipeIngredient::class, mappedBy="recipe", orphanRemoval=true)
     */
    private $recipeIngredients;

    /**
     * @ORM\ManyToMany(targetEntity=Meal::class, inversedBy="recipes")
     */
    private $meals;

    /**
     * @var ?Image
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade="all", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $image = null;

    /**
     * @var bool
     */
    private $deleteImage;

    /**
     * @var ?User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recipes")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Step::class, mappedBy="recipe", orphanRemoval=true, cascade="all")
     */
    private $step;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
        $this->meals = new ArrayCollection();
        $this->step = new ArrayCollection();
        $this->dateCreate = new \DateTime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of dateCreate.
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set the value of dateCreate.
     *
     * @param \DateTime $dateCreate
     *
     * @return self
     */
    public function setDateCreate(\DateTime $dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->dateUpdate = new \DateTime();
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDeleteImage()
    {
        return $this->deleteImage;
    }

    public function setDeleteImage(bool $deleteImage)
    {
        $this->deleteImage = $deleteImage;
        if ($deleteImage) {
            $this->image = null;
        }

        return $this;
    }

    /**
     * @return Collection|RecipeIngredient[]
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients[] = $recipeIngredient;
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->removeElement($recipeIngredient);
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Meal[]
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meal): self
    {
        if (!$this->meals->contains($meal)) {
            $this->meals[] = $meal;
        }

        return $this;
    }

    public function removeMeal(Meal $meal): self
    {
        if ($this->meals->contains($meal)) {
            $this->meals->removeElement($meal);
        }

        return $this;
    }
    
    /**
     * Set the value of meals
     *
     * @return  self
     */ 
    public function setMeals($meals)
    {
        $this->meals = $meals;

        return $this;
    }

    /**
     * Get the value of image.
     *
     * @return ?Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image.
     *
     * @param ?Image $image
     *
     * @return self
     */
    public function setImage(?Image $image)
    {
        if (empty($image->getFile())) {
            $image = null;
        }
        $this->image = $image;

        return $this;
    }

    /**
     * @return ?User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param ?User $user
     *
     * @return self
     */
    public function setUser(?User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Step[]
     */
    public function getStep(): Collection
    {
        return $this->step;
    }

    public function addStep(Step $step): self
    {
        if (!$this->step->contains($step)) {
            $this->step[] = $step;
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->step->contains($step)) {
            $this->step->removeElement($step);
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }

        return $this;
    }
}

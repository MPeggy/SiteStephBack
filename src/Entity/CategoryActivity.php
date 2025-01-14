<?php

namespace App\Entity;

use App\Repository\CategoryActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryActivityRepository::class)]
class CategoryActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'categoryActivity')]
    private Collection $Activity;

    public function __construct()
    {
        $this->Activity = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivity(): Collection
    {
        return $this->Activity;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->Activity->contains($activity)) {
            $this->Activity->add($activity);
            $activity->setCategoryActivity($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->Activity->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getCategoryActivity() === $this) {
                $activity->setCategoryActivity(null);
            }
        }

        return $this;
    }
}

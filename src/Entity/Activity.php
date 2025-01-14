<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $tarif = null;

    #[ORM\Column(nullable: true)]
    private ?array $daysopen = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $amOpeningTime = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $pmOpeningtime = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Picture>
     */
    #[ORM\OneToMany(targetEntity: Picture::class, mappedBy: 'Activity', orphanRemoval: true)]
    private Collection $pictures;

    #[ORM\ManyToOne(inversedBy: 'Activity')]
    #[ORM\JoinColumn(nullable: true)]
    private ?CategoryActivity $categoryActivity = null;

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'activity')]
    private Collection $RendezVous;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'activity')]
    private Collection $Avis;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->RendezVous = new ArrayCollection();
        $this->Avis = new ArrayCollection();
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

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): static
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getDaysopen(): ?array
    {
        return $this->daysopen;
    }

    public function setDaysopen(?array $daysopen): static
    {
        $this->daysopen = $daysopen;

        return $this;
    }

    public function getAmOpeningTime(): array
    {
        return $this->amOpeningTime;
    }

    public function setAmOpeningTime(array $amOpeningTime): static
    {
        $this->amOpeningTime = $amOpeningTime;

        return $this;
    }

    public function getPmOpeningtime(): array
    {
        return $this->pmOpeningtime;
    }

    public function setPmOpeningtime(array $pmOpeningtime): static
    {
        $this->pmOpeningtime = $pmOpeningtime;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setActivity($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getActivity() === $this) {
                $picture->setActivity(null);
            }
        }

        return $this;
    }

    public function getCategoryActivity(): ?CategoryActivity
    {
        return $this->categoryActivity;
    }

    public function setCategoryActivity(?CategoryActivity $categoryActivity): static
    {
        $this->categoryActivity = $categoryActivity;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVous(): Collection
    {
        return $this->RendezVous;
    }

    public function addRendezVou(RendezVous $rendezVou): static
    {
        if (!$this->RendezVous->contains($rendezVou)) {
            $this->RendezVous->add($rendezVou);
            $rendezVou->setActivity($this);
        }

        return $this;
    }

    public function removeRendezVou(RendezVous $rendezVou): static
    {
        if ($this->RendezVous->removeElement($rendezVou)) {
            // set the owning side to null (unless already changed)
            if ($rendezVou->getActivity() === $this) {
                $rendezVou->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->Avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->Avis->contains($avi)) {
            $this->Avis->add($avi);
            $avi->setActivity($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->Avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getActivity() === $this) {
                $avi->setActivity(null);
            }
        }

        return $this;
    }
}

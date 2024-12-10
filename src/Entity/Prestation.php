<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 64)]
    private ?string $openingDate = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $amOpenIngTime = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $pmOpeningTime = [];

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    /**
     * @var Collection<int, Picture>
     */
    #[ORM\OneToMany(targetEntity: Picture::class, mappedBy: 'prestation')]
    private Collection $pictures;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\ManyToMany(targetEntity: Booking::class, mappedBy: 'prestation')]
    private Collection $bookings;

    /**
     * @var Collection<int, PrestaCategory>
     */
    #[ORM\OneToMany(targetEntity: PrestaCategory::class, mappedBy: 'prestation')]
    private Collection $prestaCategories;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->prestaCategories = new ArrayCollection();
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

    public function getOpeningDate(): ?string
    {
        return $this->openingDate;
    }

    public function setOpeningDate(string $openingDate): static
    {
        $this->openingDate = $openingDate;

        return $this;
    }

    public function getAmOpenIngTime(): array
    {
        return $this->amOpenIngTime;
    }

    public function setAmOpenIngTime(array $amOpenIngTime): static
    {
        $this->amOpenIngTime = $amOpenIngTime;

        return $this;
    }

    public function getPmOpeningTime(): array
    {
        return $this->pmOpeningTime;
    }

    public function setPmOpeningTime(array $pmOpeningTime): static
    {
        $this->pmOpeningTime = $pmOpeningTime;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

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

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

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
            $picture->setPrestation($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
    if ($this->pictures->removeElement($picture) && $picture->getPrestation() === $this) {
        $picture->setPrestation(null);
    }

    return $this;
}

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->addPrestation($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            $booking->removePrestation($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PrestaCategory>
     */
    public function getPrestaCategories(): Collection
    {
        return $this->prestaCategories;
    }

    public function addPrestaCategory(PrestaCategory $prestaCategory): static
    {
        if (!$this->prestaCategories->contains($prestaCategory)) {
            $this->prestaCategories->add($prestaCategory);
            $prestaCategory->setPrestation($this);
        }

        return $this;
    }

    public function removePrestaCategory(PrestaCategory $prestaCategory): static
    {
        if ($this->prestaCategories->removeElement($prestaCategory) && $prestaCategory->getPrestation() === $this) {
        $prestaCategory->setPrestation(null);
    }

    return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $nameUser = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $orderHour = null;

    #[ORM\Column(nullable: true)]
    private ?array $amOpeningTime = null;

    #[ORM\Column(nullable: true)]
    private ?array $pmOpeningTime = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameUser(): ?string
    {
        return $this->nameUser;
    }

    public function setNameUser(string $nameUser): static
    {
        $this->nameUser = $nameUser;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getOrderHour(): ?\DateTimeInterface
    {
        return $this->orderHour;
    }

    public function setOrderHour(\DateTimeInterface $orderHour): static
    {
        $this->orderHour = $orderHour;

        return $this;
    }

    public function getAmOpeningTime(): ?array
    {
        return $this->amOpeningTime;
    }

    public function setAmOpeningTime(?array $amOpeningTime): static
    {
        $this->amOpeningTime = $amOpeningTime;

        return $this;
    }

    public function getPmOpeningTime(): ?array
    {
        return $this->pmOpeningTime;
    }

    public function setPmOpeningTime(?array $pmOpeningTime): static
    {
        $this->pmOpeningTime = $pmOpeningTime;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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
}

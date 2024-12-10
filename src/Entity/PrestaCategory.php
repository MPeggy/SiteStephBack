<?php

namespace App\Entity;

use App\Repository\PrestaCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestaCategoryRepository::class)]
class PrestaCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'prestaCategories')]
    private ?prestation $prestation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrestation(): ?prestation
    {
        return $this->prestation;
    }

    public function setPrestation(?prestation $prestation): static
    {
        $this->prestation = $prestation;

        return $this;
    }
}

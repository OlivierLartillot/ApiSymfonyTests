<?php

namespace App\Entity;

use App\Repository\StarWarsPeopleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StarWarsPeopleRepository::class)]
class StarWarsPeople
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["StarWarsOnePeople"])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["StarWarsOnePeople"])]
    private ?bool $gender = null;

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

    public function isGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(?bool $gender): static
    {
        $this->gender = $gender;

        return $this;
    }
}

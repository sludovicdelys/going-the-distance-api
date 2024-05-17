<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['run:read', 'run:write'])]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['run:read', 'run:write'])]
    private ?string $username;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Run::class)]
    #[Groups(['user:read'])]
    private Collection $runs;

    public function __construct()
    {
        $this->runs = new ArrayCollection();
    }

    // Add getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return Collection<int, Run>
     */
    public function getRuns(): Collection
    {
        return $this->runs;
    }
}
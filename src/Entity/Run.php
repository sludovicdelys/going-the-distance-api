<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RunRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\GetRunsByUserController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RunRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            openapiContext: [
                'summary' => 'Get a list of Runs.',
                'description' => '<b>EN</b> – Retrieves an array of runs.<br><b>FR</b> – Retourne un tableau de courses à pied.',
            ],
            normalizationContext: ['groups' => ['run:read']],
        ),
        new Get(
            openapiContext: [
                'summary' => 'Get a Run.',
                'description' => '<b>EN</b> – Retrieves a Run resource.<br><b>FR</b> – Retourne une course à pied détaillé.',
            ],
            normalizationContext: ['groups' => ['run:read']],
        ),
        new GetCollection(
            uriTemplate: '/users/{id}/runs',
            controller: GetRunsByUserController::class,
            openapiContext: [
                'summary' => 'Get a list of Runs for a User.',
                'description' => '<b>EN</b> – Retrieves an array of runs for a specific user.<br><b>FR</b> – Retourne un tableau de courses à pied pour un utilisateur spécifique.',
            ],
            normalizationContext: ['groups' => ['run:read']],
        ),
        new Post(
            security: "is_granted('ROLE_FRONTEND')",
            openapiContext: [
                'summary' => 'Create a new Run.',
                'description' => '<b>EN</b> – Creates a new run resource.<br><b>FR</b> – Crée une nouvelle course à pied.',
            ],
            denormalizationContext: ['groups' => ['run:write']],
        ),
        new Put(
            security: "is_granted('ROLE_FRONTEND')",
            openapiContext: [
                'summary' => 'Update a Run.',
                'description' => '<b>EN</b> – Updates a run resource.<br><b>FR</b> – Met à jour une course à pied.',
            ],
            denormalizationContext: ['groups' => ['run:write']],
        ),
        new Delete(
            security: "is_granted('ROLE_FRONTEND')",
            openapiContext: [
                'summary' => 'Delete a Run.',
                'description' => '<b>EN</b> – Deletes a run resource.<br><b>FR</b> – Supprime une course à pied.',
            ],
        ),
    ],
)]

class Run
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['run:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['run:read', 'run:write'])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(['run:read', 'run:write'])]
    private ?int $average_speed = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['run:read', 'run:write'])]
    private ?\DateTimeInterface $running_pace = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['run:read', 'run:write'])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['run:read', 'run:write'])]
    private ?\DateTimeInterface $start_time = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['run:read', 'run:write'])]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column]
    #[Groups(['run:read', 'run:write'])]
    private ?int $distance = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['run:read', 'run:write'])]
    private ?string $comments = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['run:read', 'run:write'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    // Add a method to get the username directly
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAverageSpeed(): ?int
    {
        return $this->average_speed;
    }

    public function setAverageSpeed(int $average_speed): static
    {
        $this->average_speed = $average_speed;

        return $this;
    }

    public function getRunningPace(): ?\DateTimeInterface
    {
        return $this->running_pace;
    }

    public function setRunningPace(\DateTimeInterface $running_pace): static
    {
        $this->running_pace = $running_pace;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }
}

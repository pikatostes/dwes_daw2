<?php

namespace App\Entity;

use App\Repository\SongsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongsRepository::class)]
class Songs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $Author = null;

    #[ORM\Column(length: 255)]
    private ?string $Cover = null;

    #[ORM\Column(length: 255)]
    private ?string $Audio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): static
    {
        $this->Author = $Author;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->Cover;
    }

    public function setCover(string $Cover): static
    {
        $this->Cover = $Cover;

        return $this;
    }

    public function getAudio(): ?string
    {
        return $this->Audio;
    }

    public function setAudio(string $Audio): static
    {
        $this->Audio = $Audio;

        return $this;
    }
}

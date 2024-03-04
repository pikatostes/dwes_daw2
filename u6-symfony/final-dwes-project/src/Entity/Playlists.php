<?php

namespace App\Entity;

use App\Repository\PlaylistsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistsRepository::class)]
class Playlists
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\ManyToMany(targetEntity: Songs::class, inversedBy: 'playlists')]
    private Collection $song_id;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    private ?Users $user_id = null;

    public function __construct()
    {
        $this->song_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    /**
     * @return Collection<int, Songs>
     */
    public function getSongId(): Collection
    {
        return $this->song_id;
    }

    public function addSongId(Songs $songId): static
    {
        if (!$this->song_id->contains($songId)) {
            $this->song_id->add($songId);
        }

        return $this;
    }

    public function removeSongId(Songs $songId): static
    {
        $this->song_id->removeElement($songId);

        return $this;
    }

    public function getUserId(): ?Users
    {
        return $this->user_id;
    }

    public function setUserId(?Users $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}

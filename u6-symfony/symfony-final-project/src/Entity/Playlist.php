<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    private ?User $user_id = null;

    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'playlists')]
    private Collection $songs_id;

    public function __construct()
    {
        $this->songs_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongsId(): Collection
    {
        return $this->songs_id;
    }

    public function addSongsId(Song $songsId): static
    {
        if (!$this->songs_id->contains($songsId)) {
            $this->songs_id->add($songsId);
        }

        return $this;
    }

    public function removeSongsId(Song $songsId): static
    {
        $this->songs_id->removeElement($songsId);

        return $this;
    }
}

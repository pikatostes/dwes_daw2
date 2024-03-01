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
    private ?string $Name = null;

    #[ORM\ManyToMany(targetEntity: Songs::class, inversedBy: 'playlists')]
    private Collection $Songs;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    private ?Users $Users = null;

    public function __construct()
    {
        $this->Song_ID = new ArrayCollection();
        $this->Songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, Songs>
     */
    public function getSongs(): Collection
    {
        return $this->Songs;
    }

    public function addSong(Songs $song): static
    {
        if (!$this->Songs->contains($song)) {
            $this->Songs->add($song);
        }

        return $this;
    }

    public function removeSong(Songs $song): static
    {
        $this->Songs->removeElement($song);

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->Users;
    }

    public function setUsers(?Users $Users): static
    {
        $this->Users = $Users;

        return $this;
    }


}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompetitionClassRepository")
 */
class CompetitionClass
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getKey(): ?int
    {
        return $this->key;
    }

    public function setKey(int $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProviderRepository::class)
 */
class Provider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    // Les dades que necesitarem guardar són: nom, correu electónic, telefon, tipo de proveidor i activitat
    // Heurem de tenir constància de quan s'ha introduit el proveidor i quan s'ha modificat
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activity;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;    // Data de creació

    /**
     * @ORM\Column(type="datetime")
     */
    public $updatedAt;    // Data de modificació


    // Constructor
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Getters i Setters de cada una de les propietats

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getActivity(): ?bool
    {
        return $this->activity;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setActivity(bool $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    // Funció per a saber quan s'ha introduit el proveidor
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    // Funció per a saber quan s'ha modificat el proveidor
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    // Funció per a que s'actualitzi la data de modificació
    // Només haurem de modificar la data de modificació de les dades. La de creació no es tocarà
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }
}

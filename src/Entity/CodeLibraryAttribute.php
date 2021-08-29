<?php

namespace Kematjaya\CodeManagerBundle\Entity;

use Kematjaya\CodeManagerBundle\Repository\CodeLibraryAttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use Kematjaya\CodeManagerBundle\Entity\CodeLibrary;

/**
 * @ORM\Entity(repositoryClass=CodeLibraryAttributeRepository::class)
 */
class CodeLibraryAttribute
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CodeLibrary::class, inversedBy="attributes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $code_library;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $last_sequence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_code;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $conditional = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeLibrary(): ?CodeLibrary
    {
        return $this->code_library;
    }

    public function setCodeLibrary(?CodeLibrary $code_library): self
    {
        $this->code_library = $code_library;

        return $this;
    }

    public function getLastSequence(): ?float
    {
        return $this->last_sequence;
    }

    public function setLastSequence(?float $last_sequence): self
    {
        $this->last_sequence = $last_sequence;

        return $this;
    }

    public function getLastCode(): ?string
    {
        return $this->last_code;
    }

    public function setLastCode(?string $last_code): self
    {
        $this->last_code = $last_code;

        return $this;
    }

    public function getConditional(): ?array
    {
        return $this->conditional;
    }

    public function setConditional(?array $conditional): self
    {
        $this->conditional = $conditional;

        return $this;
    }
}

<?php

namespace Kematjaya\CodeManagerBundle\Entity;

use Kematjaya\CodeManagerBundle\Repository\CodeLibraryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;
/**
 * @ORM\Entity(repositoryClass=CodeLibraryRepository::class)
 */
class CodeLibrary implements CodeLibraryResetInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $format;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class_name;

    /**
     * @ORM\Column(name="separator_field", type="string", length=255)
     */
    private $separator;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_used;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $last_sequence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_key;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $length;
    
    /**
     * @ORM\OneToMany(targetEntity=CodeLibraryAttribute::class, mappedBy="code_library")
     */
    private $attributes;
    
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }
    
    public function getId(): ?\Symfony\Component\Uid\Uuid
    {
        return $this->id;
    }

    public static function getSeparators()
    {
        return [
            self::SEPARATOR_BACKSLASH,
            self::SEPARATOR_MINUS,
            self::SEPARATOR_SLASH
        ];
    }
    
    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->class_name;
    }

    public function setClassName(string $class_name): self
    {
        $this->class_name = $class_name;

        return $this;
    }

    public function getSeparator(): ?string
    {
        return $this->separator;
    }

    public function setSeparator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    public function getLastUsed(): ?\DateTimeInterface
    {
        return $this->last_used;
    }

    public function setLastUsed(?\DateTimeInterface $last_used): CodeLibraryInterface
    {
        $this->last_used = $last_used;

        return $this;
    }

    public function getLastSequence(): ?int
    {
        return $this->last_sequence;
    }

    public function setLastSequence(?int $last_sequence): CodeLibraryInterface
    {
        $this->last_sequence = $last_sequence;

        return $this;
    }

    public function getLastCode(): ?string
    {
        return $this->last_code;
    }

    public function setLastCode(?string $last_code): CodeLibraryInterface
    {
        $this->last_code = $last_code;

        return $this;
    }

    public function getResetKey(): ?string
    {
        return $this->reset_key;
    }

    public function setResetKey(?string $reset_key): self
    {
        $this->reset_key = $reset_key;

        return $this;
    }
    
    public function getLength():?int 
    {
        return $this->length;
    }

    public function setLength($length):self 
    {
        $this->length = $length;
        
        return $this;
    }
    
    /**
     * @return Collection|CodeLibraryAttribute[]
     */
    public function getCodeLibraryAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addCodeLibraryAttribute(CodeLibraryAttribute $attributes): self
    {
        if (!$this->attributes->contains($attributes)) {
            $this->attributes[] = $attributes;
            $attributes->setCodeLibrary($this);
        }

        return $this;
    }

    public function removeCodeLibraryAttribute(CodeLibraryAttribute $attributes): self
    {
        if ($this->attributes->removeElement($attributes)) {
            // set the owning side to null (unless already changed)
            if ($attributes->getCodeLibrary() === $this) {
                $attributes->setCodeLibrary(null);
            }
        }

        return $this;
    }

}

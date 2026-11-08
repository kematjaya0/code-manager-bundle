<?php

namespace Kematjaya\CodeManagerBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;
use Kematjaya\CodeManagerBundle\Repository\CodeLibraryRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CodeLibraryRepository::class)]
class CodeLibrary implements CodeLibraryResetInterface
{
    public const SEPARATOR_DOT = '.';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $format = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $class_name = null;

    #[ORM\Column(name: 'separator_field', type: Types::STRING, length: 255)]
    private ?string $separator = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_used = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $last_sequence = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $last_code = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $reset_key = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $length = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public static function getSeparators(): array
    {
        return [
            self::SEPARATOR_BACKSLASH,
            self::SEPARATOR_MINUS,
            self::SEPARATOR_SLASH,
            self::SEPARATOR_DOT,
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

    public function setLastUsed(?\DateTimeInterface $last_used): self
    {
        $this->last_used = $last_used;

        return $this;
    }

    public function getLastSequence(): ?int
    {
        return $this->last_sequence;
    }

    public function setLastSequence(?int $last_sequence): self
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

    public function getResetKey(): ?string
    {
        return $this->reset_key;
    }

    public function setResetKey(?string $reset_key): self
    {
        $this->reset_key = $reset_key;

        return $this;
    }
}

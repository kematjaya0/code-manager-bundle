<?php

namespace Kematjaya\CodeManagerBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;
use Kematjaya\CodeManagerBundle\Repository\CodeLibraryLogRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CodeLibraryLogRepository::class)]
class CodeLibraryLog implements CodeLibraryLogInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $class_name = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $class_id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $generated_code = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): CodeLibraryLogInterface
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->class_name;
    }

    public function setClassName(string $class_name): CodeLibraryLogInterface
    {
        $this->class_name = $class_name;

        return $this;
    }

    public function getClassId(): ?string
    {
        return $this->class_id;
    }

    public function setClassId(string $class_id): CodeLibraryLogInterface
    {
        $this->class_id = $class_id;

        return $this;
    }

    public function getGeneratedCode(): ?string
    {
        return $this->generated_code;
    }

    public function setGeneratedCode(string $generated_code): CodeLibraryLogInterface
    {
        $this->generated_code = $generated_code;

        return $this;
    }
}

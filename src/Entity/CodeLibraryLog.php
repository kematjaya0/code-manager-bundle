<?php

namespace Kematjaya\CodeManagerBundle\Entity;

use Kematjaya\CodeManagerBundle\Repository\CodeLibraryLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;

/**
 * @ORM\Entity(repositoryClass=CodeLibraryLogRepository::class)
 */
class CodeLibraryLog implements CodeLibraryLogInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class_name;

    /**
     * @ORM\Column(type="string")
     */
    private $class_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $generated_code;

    public function getId(): ?\Symfony\Component\Uid\Uuid
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

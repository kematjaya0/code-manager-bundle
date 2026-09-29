<?php

namespace Kematjaya\CodeManagerBundle\Tests\Fixtures\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

#[ORM\Entity]
class Invoice implements CodeLibraryClientInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $code = null;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private string $branch;

    public function __construct(string $branch = 'JKT')
    {
        $this->branch = $branch;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassId(): ?string
    {
        return null === $this->id ? null : (string) $this->id;
    }

    public function getLibrary(): array
    {
        return ['branch' => $this->branch];
    }

    public function getGeneratedCode(): ?string
    {
        return $this->code;
    }

    public function setGeneratedCode(string $code): CodeLibraryClientInterface
    {
        $this->code = $code;

        return $this;
    }
}

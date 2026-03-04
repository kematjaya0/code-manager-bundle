<?php

namespace Kematjaya\CodeManagerBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Kematjaya\CodeManagerBundle\Repository\CodeLibraryLogRepository;
use Kematjaya\CodeManagerBundle\Entity\CodeLibraryLog;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;
class CodeLibraryLogRepositoryTest extends TestCase
{
    private $registry;
    private $em;
    private $classMetadata;
    private $repository;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->classMetadata = $this->createMock(ClassMetadata::class);
        $this->classMetadata->name = CodeLibraryLog::class;

        $this->em->method('getClassMetadata')->willReturn($this->classMetadata);

        $this->registry->method('getManagerForClass')
            ->with(CodeLibraryLog::class)
            ->willReturn($this->em);

        $this->repository = new CodeLibraryLogRepository($this->registry);
    }

    public function testCreateLog()
    {
        $log = $this->repository->createLog();
        $this->assertInstanceOf(CodeLibraryLog::class, $log);
    }

    public function testSave()
    {
        $log = new CodeLibraryLog();

        $this->em->expects($this->once())
            ->method('persist')
            ->with($log);

        $this->repository->save($log);
    }
}

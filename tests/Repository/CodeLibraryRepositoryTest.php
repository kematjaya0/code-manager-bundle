<?php

namespace Kematjaya\CodeManagerBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Kematjaya\CodeManagerBundle\Repository\CodeLibraryRepository;
use Kematjaya\CodeManagerBundle\Entity\CodeLibrary;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;

class CodeLibraryRepositoryTest extends TestCase
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
        $this->classMetadata->name = CodeLibrary::class;

        $this->em->method('getClassMetadata')->willReturn($this->classMetadata);

        $this->registry->method('getManagerForClass')
            ->with(CodeLibrary::class)
            ->willReturn($this->em);

        $this->repository = new CodeLibraryRepository($this->registry);
    }

    public function testFindOneByClient()
    {
        $client = $this->createMock(CodeLibraryClientInterface::class);
        $clientClass = get_class($client);

        $persister = $this->createMock(\Doctrine\ORM\Persisters\Entity\EntityPersister::class);
        $unitOfWork = $this->createMock(\Doctrine\ORM\UnitOfWork::class);

        $this->em->method('getUnitOfWork')->willReturn($unitOfWork);
        $unitOfWork->method('getEntityPersister')->willReturn($persister);

        $library = new CodeLibrary();

        $persister->expects($this->once())
            ->method('load')
            ->with(['class_name' => $clientClass], null, null, [], null, 1, null)
            ->willReturn($library);

        $result = $this->repository->findOneByClient($client);
        $this->assertSame($library, $result);
    }

    public function testSave()
    {
        $library = new CodeLibrary();

        $this->em->expects($this->once())
            ->method('persist')
            ->with($library);

        $this->repository->save($library);
    }
}

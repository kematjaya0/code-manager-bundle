<?php

namespace Kematjaya\CodeManagerBundle\Tests\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Manager\CodeManagerInterface;
use Kematjaya\CodeManagerBundle\Listener\CodeManagerListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CodeManagerListenerTest extends TestCase
{
    /**
     * @param EntityManagerInterface&MockObject $em
     */
    private function createEventArgs(EntityManagerInterface $em, array $insertions): PreFlushEventArgs
    {
        $uow = $this->createMock(UnitOfWork::class);
        $uow->method('getScheduledEntityInsertions')->willReturn($insertions);
        /** @var MockObject $mockEm */
        $mockEm = $em;
        $mockEm->method('getUnitOfWork')->willReturn($uow);

        return new PreFlushEventArgs($em);
    }

    public function testPreFlushWithValidEntity(): void
    {
        $codeManager = $this->createMock(CodeManagerInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $entity = $this->createMock(CodeLibraryClientInterface::class);
        $codeLibrary = $this->createMock(CodeLibraryClientInterface::class);

        $entity->method('getGeneratedCode')->willReturn(null);
        $codeLibrary->method('getGeneratedCode')->willReturn('NEW-CODE-001');
        $codeManager->expects($this->once())->method('generate')->with($entity)->willReturn($codeLibrary);
        $entity->expects($this->once())->method('setGeneratedCode')->with('NEW-CODE-001');
        $em->expects($this->once())->method('persist')->with($entity);

        $listener = new CodeManagerListener($codeManager);
        $listener->preFlush($this->createEventArgs($em, [$entity]));
    }

    public function testPreFlushWithEntityAlreadyHavingCode(): void
    {
        $codeManager = $this->createMock(CodeManagerInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $entity = $this->createMock(CodeLibraryClientInterface::class);
        $entity->method('getGeneratedCode')->willReturn('EXISTING-CODE');

        $codeManager->expects($this->never())->method('generate');
        $em->expects($this->never())->method('persist');

        $listener = new CodeManagerListener($codeManager);
        $listener->preFlush($this->createEventArgs($em, [$entity]));
    }

    public function testPreFlushWithInvalidEntity(): void
    {
        $codeManager = $this->createMock(CodeManagerInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $codeManager->expects($this->never())->method('generate');
        $em->expects($this->never())->method('persist');

        $listener = new CodeManagerListener($codeManager);
        $listener->preFlush($this->createEventArgs($em, [new \stdClass()]));
    }
}

<?php

namespace Kematjaya\CodeManagerBundle\Tests\Listener;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManagerBundle\Listener\CodeManagerListener;
use Kematjaya\CodeManager\Manager\CodeManagerInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class CodeManagerListenerTest extends TestCase
{
    public function testPreFlushWithValidEntity()
    {
        $codeManager = $this->createMock(CodeManagerInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $uow = $this->createMock(UnitOfWork::class);
        $eventArgs = $this->createMock(PreFlushEventArgs::class);

        $entity = $this->createMock(CodeLibraryClientInterface::class);
        $codeLibraryClient = $this->createMock(CodeLibraryClientInterface::class);

        $eventArgs->method('getEntityManager')->willReturn($em);
        $em->method('getUnitOfWork')->willReturn($uow);

        // Entity without code
        $entity->method('getGeneratedCode')->willReturn(null);
        $uow->method('getScheduledEntityInsertions')->willReturn([$entity]);

        $codeLibraryClient->method('getGeneratedCode')->willReturn('NEW-CODE-001');
        $codeManager->expects($this->once())->method('generate')->with($entity)->willReturn($codeLibraryClient);
        $entity->expects($this->once())->method('setGeneratedCode')->with('NEW-CODE-001');
        $em->expects($this->once())->method('persist')->with($entity);

        $listener = new CodeManagerListener($codeManager);
        $listener->preFlush($eventArgs);
    }

    public function testPreFlushWithEntityAlreadyHavingCode()
    {
        $codeManager = $this->createMock(CodeManagerInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $uow = $this->createMock(UnitOfWork::class);
        $eventArgs = $this->createMock(PreFlushEventArgs::class);

        $entity = $this->createMock(CodeLibraryClientInterface::class);

        $eventArgs->method('getEntityManager')->willReturn($em);
        $em->method('getUnitOfWork')->willReturn($uow);

        // Entity WITH code
        $entity->method('getGeneratedCode')->willReturn('EXISTING-CODE');
        $uow->method('getScheduledEntityInsertions')->willReturn([$entity]);

        $codeManager->expects($this->never())->method('generate');
        $em->expects($this->never())->method('persist');

        $listener = new CodeManagerListener($codeManager);
        $listener->preFlush($eventArgs);
    }

    public function testPreFlushWithInvalidEntity()
    {
        $codeManager = $this->createMock(CodeManagerInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $uow = $this->createMock(UnitOfWork::class);
        $eventArgs = $this->createMock(PreFlushEventArgs::class);

        $entity = new \stdClass();

        $eventArgs->method('getEntityManager')->willReturn($em);
        $em->method('getUnitOfWork')->willReturn($uow);

        $uow->method('getScheduledEntityInsertions')->willReturn([$entity]);

        $codeManager->expects($this->never())->method('generate');
        $em->expects($this->never())->method('persist');

        $listener = new CodeManagerListener($codeManager);
        $listener->preFlush($eventArgs);
    }
}

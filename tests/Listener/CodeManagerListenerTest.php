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
    private function createEventArgs(EntityManagerInterface $em, array $insertions): PreFlushEventArgs
    {
        $uow = $this->createMock(UnitOfWork::class);
        $uow->method('getScheduledEntityInsertions')->willReturn($insertions);
        $em->method('getUnitOfWork')->willReturn($uow);

        return new PreFlushEventArgs($em);
    }

    public function testPreFlushWithValidEntity()
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

    public function testPreFlushWithEntityAlreadyHavingCode()
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

    public function testPreFlushWithInvalidEntity()
    {
        $codeManager = $this->createMock(CodeManagerInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $codeManager->expects($this->never())->method('generate');
        $em->expects($this->never())->method('persist');

        $listener = new CodeManagerListener($codeManager);
        $listener->preFlush($this->createEventArgs($em, [new \stdClass()]));
    }
}

<?php

namespace Kematjaya\CodeManagerBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Manager\CodeManagerInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManagerListener
{
    public function __construct(
        private readonly CodeManagerInterface $codeManager
    ) {
    }

    public function preFlush(PreFlushEventArgs $event): void
    {
        $em = $event->getObjectManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$this->allowTogenerate($entity)) {
                continue;
            }

            $this->generateCode($entity, $em);
        }
    }

    protected function allowTogenerate(object $entity): bool
    {
        if (!$entity instanceof CodeLibraryClientInterface) {
            return false;
        }

        if (null !== $entity->getGeneratedCode()) {
            return false;
        }

        return true;
    }

    private function generateCode(CodeLibraryClientInterface $entity, EntityManagerInterface $em): void
    {
        $codeLibrary = $this->codeManager->generate($entity);
        $entity->setGeneratedCode($codeLibrary->getGeneratedCode());
        $em->persist($entity);
    }
}

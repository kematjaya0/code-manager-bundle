<?php

namespace Kematjaya\CodeManagerBundle\Listener;

use Kematjaya\CodeManager\Manager\CodeManagerInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManagerListener 
{
    
    /**
     * 
     * @var CodeManagerInterface
     */
    private $codeManager;
    
    public function __construct(CodeManagerInterface $codeManager) 
    {
        $this->codeManager = $codeManager;
    }
    
    public function preFlush(PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();
        foreach($uow->getScheduledEntityInsertions() as $entity) {
            if(!$this->allowTogenerate($entity)) {
                continue;
            }
            
            $this->generateCode($entity, $em);
        }   
    }
         
    protected function allowTogenerate($entity):bool
    {
        if(!$entity instanceof CodeLibraryClientInterface) {
            return false;
        }

        if(null != $entity->getGeneratedCode()) {
            return false;
        }
        
        return true;
    }
    
    private function generateCode(CodeLibraryClientInterface $entity, EntityManagerInterface $em):void
    {
        $codeLibrary = $this->codeManager->generate($entity);
        $entity->setGeneratedCode($codeLibrary->getGeneratedCode());
        $em->persist($entity);
    }
}

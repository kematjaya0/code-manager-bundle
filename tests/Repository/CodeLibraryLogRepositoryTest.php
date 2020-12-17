<?php

namespace Kematjaya\CodeManagerBundle\Tests\Repository;

use Kematjaya\CodeManagerBundle\Tests\Model\CodeLibraryLog;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryLogRepositoryTest implements CodeLibraryLogRepositoryInterface
{
    
    public function createLog(): CodeLibraryLogInterface 
    {
        return new CodeLibraryLog();
    }

    public function save(CodeLibraryLogInterface $object): void 
    {
        
    }

}

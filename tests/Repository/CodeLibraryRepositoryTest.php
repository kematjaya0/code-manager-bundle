<?php

namespace Kematjaya\CodeManagerBundle\Tests\Repository;

use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryRepositoryTest implements CodeLibraryRepositoryInterface
{
    
    public function findOneByClient(CodeLibraryClientInterface $client): ?CodeLibraryInterface 
    {
        return null;
    }

    public function save(CodeLibraryInterface $object): void 
    {
        
    }

}

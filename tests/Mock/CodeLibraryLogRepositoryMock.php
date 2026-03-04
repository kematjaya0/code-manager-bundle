<?php

namespace Kematjaya\CodeManagerBundle\Tests\Mock;

use Kematjaya\CodeManagerBundle\Tests\Model\CodeLibraryLog;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryLogRepositoryMock implements CodeLibraryLogRepositoryInterface
{

    public function createLog(): CodeLibraryLogInterface
    {
        return new CodeLibraryLog();
    }

    public function save(CodeLibraryLogInterface $object): void
    {

    }

}

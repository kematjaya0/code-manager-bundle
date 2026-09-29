<?php

namespace Kematjaya\CodeManagerBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManagerBundle\Entity\CodeLibraryLog;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryLogTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $entity = new CodeLibraryLog();

        $date = new \DateTime();
        $entity->setCreatedAt($date);
        $this->assertEquals($date, $entity->getCreatedAt());

        $entity->setClassName('App\Entity\SomeClass');
        $this->assertEquals('App\Entity\SomeClass', $entity->getClassName());

        $entity->setClassId('some-uuid-or-id');
        $this->assertEquals('some-uuid-or-id', $entity->getClassId());

        $entity->setGeneratedCode('CODE-999');
        $this->assertEquals('CODE-999', $entity->getGeneratedCode());
    }
}

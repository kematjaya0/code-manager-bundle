<?php

namespace Kematjaya\CodeManagerBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManagerBundle\Entity\CodeLibrary;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $entity = new CodeLibrary();

        $entity->setFormat('YY/MM/DD');
        $this->assertEquals('YY/MM/DD', $entity->getFormat());

        $entity->setClassName('App\Entity\SomeClass');
        $this->assertEquals('App\Entity\SomeClass', $entity->getClassName());

        $entity->setSeparator('/');
        $this->assertEquals('/', $entity->getSeparator());

        $date = new \DateTime();
        $entity->setLastUsed($date);
        $this->assertEquals($date, $entity->getLastUsed());

        $entity->setLastSequence(10);
        $this->assertEquals(10, $entity->getLastSequence());

        $entity->setLastCode('CODE-001');
        $this->assertEquals('CODE-001', $entity->getLastCode());

        $entity->setResetKey('monthly');
        $this->assertEquals('monthly', $entity->getResetKey());

        $entity->setLength(10);
        $this->assertEquals(10, $entity->getLength());
    }

    public function testGetSeparators()
    {
        $separators = CodeLibrary::getSeparators();
        $this->assertIsArray($separators);
        // Using constants from interface CodeLibraryInterface which CodeLibrary should implement
        $this->assertTrue(in_array('\\', $separators) || in_array('/', $separators) || in_array('-', $separators));
    }
}

<?php

namespace Kematjaya\CodeManagerBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Kematjaya\CodeManager\Builder\CodeBuilderInterface;
use Kematjaya\CodeManager\Manager\CodeManagerInterface;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManagerInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManagerBundleTest extends WebTestCase 
{
    public function testInstance()
    {
        $client = parent::createClient();
        $container = $client->getContainer();
        $this->assertInstanceOf(CodeBuilderInterface::class, $container->get('kmj.code_builder'));
        $this->assertInstanceOf(CodeManagerInterface::class, $container->get('kmj.code_manager'));
        $this->assertInstanceOf(CodeLibraryLogManagerInterface::class, $container->get('kmj.code_log_manager'));
    }
    
    
    public static function getKernelClass() 
    {
        return AppKernelTest::class;
    }
}

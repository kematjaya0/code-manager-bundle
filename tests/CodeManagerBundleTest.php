<?php

namespace Kematjaya\CodeManagerBundle\Tests;

use Kematjaya\CodeManager\Builder\CodeBuilderInterface;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManagerInterface;
use Kematjaya\CodeManager\Manager\CodeManagerInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManagerBundle\Repository\CodeLibraryLogRepository;
use Kematjaya\CodeManagerBundle\Repository\CodeLibraryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManagerBundleTest extends KernelTestCase
{
    public function testInstance(): void
    {
        $container = static::getContainer();
        $this->assertInstanceOf(CodeBuilderInterface::class, $container->get('kmj.code_builder'));
        $this->assertInstanceOf(CodeManagerInterface::class, $container->get('kmj.code_manager'));
        $this->assertInstanceOf(CodeLibraryLogManagerInterface::class, $container->get('kmj.code_log_manager'));
    }

    public function testDefaultRepositories(): void
    {
        $container = static::getContainer();
        $this->assertInstanceOf(
            CodeLibraryRepository::class,
            $container->get(CodeLibraryRepositoryInterface::class)
        );
        $this->assertInstanceOf(
            CodeLibraryLogRepository::class,
            $container->get(CodeLibraryLogRepositoryInterface::class)
        );
    }

    protected static function getKernelClass(): string
    {
        return AppKernel::class;
    }
}

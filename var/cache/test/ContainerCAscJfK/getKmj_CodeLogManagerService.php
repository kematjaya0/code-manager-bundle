<?php

namespace ContainerCAscJfK;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getKmj_CodeLogManagerService extends Kematjaya_CodeManagerBundle_Test_AppKernelTestTestDebugContainer
{
    /**
     * Gets the public 'kmj.code_log_manager' shared autowired service.
     *
     * @return \Kematjaya\CodeManager\Manager\CodeLibraryLogManager
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/kematjaya/code-manager/src/Manager/CodeLibraryLogManagerInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/kematjaya/code-manager/src/Manager/CodeLibraryLogManager.php';
        include_once \dirname(__DIR__, 4).'/vendor/kematjaya/code-manager/src/Repository/CodeLibraryLogRepositoryInterface.php';
        include_once \dirname(__DIR__, 4).'/tests/Repository/CodeLibraryLogRepositoryTest.php';

        return $container->services['kmj.code_log_manager'] = new \Kematjaya\CodeManager\Manager\CodeLibraryLogManager(($container->privates['Kematjaya\\CodeManager\\Repository\\CodeLibraryLogRepositoryInterface'] ?? ($container->privates['Kematjaya\\CodeManager\\Repository\\CodeLibraryLogRepositoryInterface'] = new \Kematjaya\CodeManagerBundle\Tests\Repository\CodeLibraryLogRepositoryTest())));
    }
}
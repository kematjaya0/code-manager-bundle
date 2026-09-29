<?php

namespace Kematjaya\CodeManagerBundle\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Kematjaya\CodeManagerBundle\Entity\CodeLibrary;
use Kematjaya\CodeManagerBundle\Entity\CodeLibraryLog;
use Kematjaya\CodeManagerBundle\Repository\CodeLibraryRepository;
use Kematjaya\CodeManagerBundle\Tests\AppKernel;
use Kematjaya\CodeManagerBundle\Tests\Fixtures\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

/**
 * Listener + entity + repository bundle dengan database sqlite
 */
class GenerateCodeTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    protected static function getKernelClass(): string
    {
        return AppKernel::class;
    }

    protected function setUp(): void
    {
        $this->em = static::getContainer()->get('doctrine.orm.entity_manager');
        (new SchemaTool($this->em))->createSchema($this->em->getMetadataFactory()->getAllMetadata());
    }

    private function createLibrary(string $format, string $separator, ?string $resetKey = null, ?int $length = null): CodeLibrary
    {
        $library = (new CodeLibrary())
            ->setClassName(Invoice::class)
            ->setFormat($format)
            ->setSeparator($separator)
            ->setResetKey($resetKey)
            ->setLength($length);
        $this->em->persist($library);
        $this->em->flush();

        return $library;
    }

    public function testGenerateCodeOnFlush()
    {
        $library = $this->createLibrary('INV/{number}/{YYYY}', '/');
        $this->assertInstanceOf(Uuid::class, $library->getId());

        $first = new Invoice();
        $this->em->persist($first);
        $this->em->flush();

        $second = new Invoice();
        $this->em->persist($second);
        $this->em->flush();

        $this->assertSame('INV/0001/' . date('Y'), $first->getGeneratedCode());
        $this->assertSame('INV/0002/' . date('Y'), $second->getGeneratedCode());

        $this->em->clear();
        $library = $this->em->getRepository(CodeLibrary::class)->findOneBy(['class_name' => Invoice::class]);
        $this->assertSame(2, $library->getLastSequence());
        $this->assertSame('INV/0002/' . date('Y'), $library->getLastCode());
        $this->assertNotNull($library->getLastUsed());

        $logs = $this->em->getRepository(CodeLibraryLog::class)->findBy([], ['generated_code' => 'ASC']);
        $this->assertCount(2, $logs);
        $this->assertSame(Invoice::class, $logs[0]->getClassName());
        $this->assertSame('INV/0001/' . date('Y'), $logs[0]->getGeneratedCode());
    }

    public function testExistingCodeIsKept()
    {
        $this->createLibrary('INV-{number}', '-');

        $invoice = (new Invoice())->setGeneratedCode('MANUAL-1');
        $this->em->persist($invoice);
        $this->em->flush();

        $this->assertSame('MANUAL-1', $invoice->getGeneratedCode());
        $this->assertCount(0, $this->em->getRepository(CodeLibraryLog::class)->findAll());
    }

    public function testResetSequenceByClientLibrary()
    {
        $this->createLibrary('{branch}.{number}', '.', '{branch}', 3);

        $codes = [];
        foreach (['JKT', 'JKT', 'SBY', 'SBY'] as $branch) {
            $invoice = new Invoice($branch);
            $this->em->persist($invoice);
            $this->em->flush();
            $codes[] = $invoice->getGeneratedCode();
        }

        $this->assertSame(['JKT.001', 'JKT.002', 'SBY.001', 'SBY.002'], $codes);
    }

    public function testFilterClass()
    {
        $this->createLibrary('INV-{number}', '-');

        /** @var CodeLibraryRepository $repo */
        $repo = $this->em->getRepository(CodeLibrary::class);
        $this->assertSame([], $repo->filterClass([]));
        $this->assertSame([1 => 'App\Entity\Other'], $repo->filterClass([Invoice::class, 'App\Entity\Other']));
    }
}

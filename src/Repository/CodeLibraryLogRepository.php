<?php

namespace Kematjaya\CodeManagerBundle\Repository;

use Kematjaya\CodeManagerBundle\Entity\CodeLibraryLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;

/**
 * @method CodeLibraryLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeLibraryLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeLibraryLog[]    findAll()
 * @method CodeLibraryLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeLibraryLogRepository extends ServiceEntityRepository implements CodeLibraryLogRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeLibraryLog::class);
    }

    public function createLog(): CodeLibraryLogInterface 
    {
        return new CodeLibraryLog();
    }

    public function save(CodeLibraryLogInterface $object): void 
    {
        $this->_em->persist($object);
    }

}

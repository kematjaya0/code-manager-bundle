<?php

namespace Kematjaya\CodeManagerBundle\Repository;

use Kematjaya\CodeManagerBundle\Entity\CodeLibrary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;

/**
 * @method CodeLibrary|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeLibrary|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeLibrary[]    findAll()
 * @method CodeLibrary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeLibraryRepository extends ServiceEntityRepository implements CodeLibraryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeLibrary::class);
    }

    public function findOneByClient(CodeLibraryClientInterface $client): ?CodeLibraryInterface 
    {
        return $this->findOneBy(['class_name' => get_class($client)]);
    }

    public function save(CodeLibraryInterface $object): void 
    {
        $this->_em->persist($object);
    }
    
    public function filterClass(array $className):array
    {
        return array_filter($className, function ($value) {
            $qb = $this->createQueryBuilder('t');
            $qb->where('t.class_name = :class_name')
                    ->setParameter('class_name', $value)
                    ->setMaxResults(1);
            
            return $qb->getQuery()->getOneOrNullResult() ? false : true;
        });
    }

}

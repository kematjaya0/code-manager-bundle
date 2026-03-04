<?php

namespace Kematjaya\CodeManagerBundle\Repository;

use Kematjaya\CodeManagerBundle\Entity\CodeLibrary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Entity\CodeManagerClientInterface;


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
        if (!$client instanceof CodeManagerClientInterface) {

            return $this->findOneBy(['class_name' => get_class($client)]);
        }

        return $this->findOneBy(['class_name' => $client->getClientClassName()]);
    }

    public function save(CodeLibraryInterface $object): void
    {
        $this->_em->persist($object);
    }

    public function filterClass(array $className): array
    {
        if (empty($className)) {
            return [];
        }

        $qb = $this->createQueryBuilder('t');
        $qb->select('t.class_name')
            ->where($qb->expr()->in('t.class_name', ':class_names'))
            ->setParameter('class_names', $className);

        $existingClasses = array_column((array) $qb->getQuery()->getArrayResult(), 'class_name');

        return array_filter($className, function ($value) use ($existingClasses) {
            return !in_array($value, $existingClasses);
        });
    }

}

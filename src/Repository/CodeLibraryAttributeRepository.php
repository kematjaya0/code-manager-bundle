<?php

namespace Kematjaya\CodeManagerBundle\Repository;

use Kematjaya\CodeManagerBundle\Entity\CodeLibrary;
use Kematjaya\CodeManagerBundle\Entity\CodeLibraryAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodeLibraryAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeLibraryAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeLibraryAttribute[]    findAll()
 * @method CodeLibraryAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeLibraryAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeLibraryAttribute::class);
    }
    
    public function findByAdditionals(CodeLibrary $library, array $additional):CodeLibraryAttribute
    {
        $qb = $this->createQueryBuilder('t')
                ->where('t.code_library = :lib')
                ->andWhere('TEXT(t.conditional) = :params')
                ->setParameter('lib', $library)
                ->setParameter('params', json_encode($additional));
        $attribute = $qb->getQuery()->getOneOrNullResult();
        if (!$attribute) {
            $attribute = new CodeLibraryAttribute();
            $attribute->setCodeLibrary($library);
            $attribute->setConditional($additional);
            
            $this->save($attribute);
        }
        
        return $attribute;
    }
    
    public function save(CodeLibraryAttribute $attribute):void
    {
        $this->_em->persist($attribute);
    }
}

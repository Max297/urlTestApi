<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Url|null find($id, $lockMode = null, $lockVersion = null)
 * @method Url|null findOneBy(array $criteria, array $orderBy = null)
 * @method Url[]    findAll()
 * @method Url[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }

    public function findOneByHash(string $value): ?Url
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.hash = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByUrl(string $value): ?Url
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.url = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function countUniqueByDomain(string $value): ?Int
    {
        $result=$this->createQueryBuilder('u')
        ->select('u.url')
        ->distinct()
        ->where('u.url LIKE :http')
        ->orWhere('u.url LIKE :https')
        ->setParameter('http', "http://".$value."%")
        ->setParameter('https', "https://".$value."%")
        ->getQuery()
        ->getResult();

        return count($result);
    }

    public function countInPeriod(String $begin, String $end): ?Int
    {
        $result=$this->createQueryBuilder('u')
        ->select('u.createdDate')
        ->distinct()
        ->where('u.createdDate > :begin')
        ->andWhere('u.createdDate < :end')
        ->setParameter('begin', $begin)
        ->setParameter('end', $end)
        ->getQuery()
        ->getResult();

        return count($result);
    }

    public function findUnsent(): ?Array
    {
        return $this->createQueryBuilder('u')
            ->where('u.is_logged = 0')
            ->getQuery()
            ->getResult();
        ;
    }
    public function fillUnsent()
    {
        return $this->createQueryBuilder('u')

            ->update()
            ->set('u.is_logged', ':val1')
            ->where('u.is_logged = :val2')
            ->setParameters([
                'val1' => 1,
                'val2' => 0,
            ])
            ->getQuery()
            ->execute();
        
    }


    
}

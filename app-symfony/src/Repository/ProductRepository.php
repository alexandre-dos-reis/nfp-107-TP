<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Section;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function fulltextSearch(string $searchTerm)
    {
        $rsm = new ResultSetMapping();
        $rsm
            ->addEntityResult(Product::class, 'p')
            ->addFieldResult('p', 'id', 'id')
            ->addFieldResult('p', 'name', 'name')
            ->addFieldResult('p', 'comments', 'comments')
            ->addFieldResult('p', 'promotion', 'promotion')
            ->addFieldResult('p', 'image', 'image')
            ->addFieldResult('p', 'price', 'price')
            ->addFieldResult('p', 'updated_at', 'updatedAt')
            ->addFieldResult('p', 'stock', 'stock')
            ->addJoinedEntityResult(Section::class, 's', 'p', 'section')
            ->addFieldResult('s', 'section_id', 'id')
            ->addFieldResult('s', 'section_name', 'name');

        $sql = "SELECT p.id, p.name, p.comments, p.promotion, p.image, p.price, p.updated_at, p.stock, s.id as section_id, s.name as section_name " . 
                "FROM product as p " . 
                "JOIN section as s ON s.id = p.idSection ".
                "WHERE MATCH (comments) AGAINST ( ? );";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $searchTerm);

       return $query->getResult();
    }
    
    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function fullTextSearch(string $value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

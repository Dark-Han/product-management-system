<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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
    public function __construct(ManagerRegistry $registry,private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
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

    public function getProductsPerPage(int $page){
        $query=$this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Product p');

        $pagination = $this->paginator->paginate(
            $query,
            $page,
            1
        );

        return $pagination;
    }

    public function getProductsByName($name): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :name')
            ->setParameter('name', "%$name%")
            ->getQuery()
            ->getResult()
        ;
    }

}

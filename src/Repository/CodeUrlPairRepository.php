<?php

namespace App\Repository;

use App\Entity\CodeUrlPair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CodeUrlPair>
 *
 * @method CodeUrlPair|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeUrlPair|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeUrlPair[]    findAll()
 * @method CodeUrlPair[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeUrlPairRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, CodeUrlPair::class);
	}

	public function save(CodeUrlPair $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function remove(CodeUrlPair $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function issetCode(string $code): bool
	{
		$res = true;
		$codeInDB = $this->getUrlByCode($code);
		if (isset($codeInDB) && $code == $codeInDB->getCode()) {
			$res = false;
		}
		return $res;
	}

	public function getUrlByCode(string $code): CodeUrlPair|null
	{
		return $this->findOneBy([
			"code" => $code
		]);
	}

//    /**
//     * @return CodeUrlPair[] Returns an array of CodeUrlPair objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CodeUrlPair
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

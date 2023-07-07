<?php

namespace App\Repository;

use App\Entity\Short;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;


/**
 * @method Short|null find($id, $lockMode = null, $lockVersion = null)
 * @method Short|null findOneBy(array $criteria, array $orderBy = null)
 * @method Short[] findAll()
 * @method Short[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortRepository extends ServiceEntityRepository implements ObjectRepository
{

	public function __construct(
		protected ManagerRegistry $registry,
		protected EntityManagerInterface $em)
	{
		parent::__construct($registry, Short::class);
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

	public function getUrlByCode(string $code): Short|null
	{
		return $this->findOneBy([
			"code" => $code
		]);
	}
}

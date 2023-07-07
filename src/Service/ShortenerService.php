<?php

namespace App\Service;

use App\Entity\Short;
use App\Repository\ShortRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\Persistence\ObjectRepository;

class ShortenerService
{
	protected objectRepository $repository;

	/**
	 * @throws NotSupported
	 */
	public function __construct(
		protected ShortRepository $shortRepository,
		protected EntityManagerInterface $em
	)
	{

	}

	public function incrementCount(Short $entity): void
	{
		$entity->incrementCounter();
		$this->em->flush();
	}
}
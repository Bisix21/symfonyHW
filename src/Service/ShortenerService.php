<?php

namespace App\Service;

use App\Entity\CodeUrlPair;
use App\Repository\CodeUrlPairRepository;
use Doctrine\Persistence\ObjectRepository;

class ShortenerService
{
	protected objectRepository $repository;

	public function __construct(
		protected CodeUrlPairRepository $shortRepository,
	)
	{

	}

	public function incrementCount(CodeUrlPair $entity): void
	{
		$entity->incrementCounter();
		$this->shortRepository->save($entity, true);
	}
}
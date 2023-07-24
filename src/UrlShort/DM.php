<?php

namespace App\UrlShort;

use App\Entity\CodeUrlPair;
use App\Repository\CodeUrlPairRepository;
use App\UrlShort\Interface\DBInterface;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use InvalidArgumentException;

class DM implements DBInterface
{
	public function __construct(
		protected Decode                 $decode,
		protected CodeUrlPair            $codeUrlPair,
		protected CodeUrlPairRepository $codeUrlPairRepository
	)
	{
	}

	/**
	 * @throws ORMException
	 */
	public function saveToDb($data): void
	{
		// if we use random code for one same url than if-statement not working.
		if (!$this->codeUrlPairRepository->issetCode($data['code'])) {
			throw new InvalidArgumentException("You have same record: {$data['code']} => {$data['url']}");
		}
		$this->codeUrlPair->setCode($data['code']);
		$this->codeUrlPair->setUrl($data['url']);
		$this->codeUrlPair->setUserId($data['userId']);
		$this->codeUrlPairRepository->save($this->codeUrlPair, true);
	}

	/**
	 * @throws NotSupported
	 */
	public function read(string $code): string
	{
		$short = $this->codeUrlPairRepository->getUrlByCode($code);
		if (is_null($short)) {
			throw new InvalidArgumentException(" Undefined code $code");
		}
		return $this->decode->decode($short->getCode());
	}
}
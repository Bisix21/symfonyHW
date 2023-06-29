<?php

namespace App\UrlShort;

use App\Entity\Short;
use App\UrlShort\Interface\IUrlDecoder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;


class Decode implements IUrlDecoder
{
	public function __construct(
		protected EntityManagerInterface $short,
	)
	{
	}

	/**
	 * @throws NotSupported
	 */
	public function decode(string $code): string
	{
		return $this->decodeFromDM($code);
	}

	/**
	 * @throws NotSupported
	 */
	protected function decodeFromDM(string $code)
	{
		$shortRep = $this->short->getRepository(Short::class);
		$short = $shortRep->getUrlByCode($code);
		return $short->getUrl();
	}
}
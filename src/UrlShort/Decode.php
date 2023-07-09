<?php

namespace App\UrlShort;

use App\Repository\CodeUrlPairRepository;
use App\UrlShort\Interface\IUrlDecoder;
use Doctrine\ORM\Exception\NotSupported;


class Decode implements IUrlDecoder
{
	public function __construct(
		protected CodeUrlPairRepository $codeUrlPairRepository
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
	protected function decodeFromDM(string $code): string
	{
		$short = $this->codeUrlPairRepository->getUrlByCode($code);
		return $short->getUrl();
	}
}
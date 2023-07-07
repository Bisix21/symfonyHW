<?php

namespace App\UrlShort;


use App\UrlShort\Interface\IUrlEncoder;

class Encode implements IUrlEncoder
{

	private int $length;

	/**
	 * @inheritDoc
	 */
	public function encode(string $url): string
	{
		return substr(rand(1, 1000) . md5($url), 0, $this->length);
	}

	/**
	 * @param int $length
	 */
	public function setLength(int $length): void
	{
		$this->length = $length;
	}
}
<?php

namespace App\UrlShort\Interface;

use InvalidArgumentException;

interface IUrlDecoder
{
	/**
	 * @param string $code
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function decode(string $code): string;
}
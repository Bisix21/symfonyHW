<?php

namespace App\UrlShort\Services;

use InvalidArgumentException;

class Validator
{
	protected bool $status = true;

	public function link($link): bool|int
	{
		// прротокол + доменна назва . домен : порт(якщо існує)/ назва каталогу
		$pattern = '/^http|s?:\/\/[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})(:[0-9]{1,5})?(\/.*)?$/i';
		if (is_array($link)){
			$link = $link['url'];
		}
		$this->isEmpty($link);
		$res = preg_match($pattern, $link);
		if (!$res) {
			throw new InvalidArgumentException("Invalid url: $link");
		}
		return $res;
	}

	public function isEmpty($value): bool
	{
		if (empty($value)) {
			$this->status = false;
			throw new InvalidArgumentException("Invalid argument! It Empty");
		}
		return $this->status;
	}

}
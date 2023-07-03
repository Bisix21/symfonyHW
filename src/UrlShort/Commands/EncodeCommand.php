<?php

namespace App\UrlShort\Commands;

use App\UrlShort\Encode;
use App\UrlShort\Interface\CommandInterface;
use App\UrlShort\Repository\DM;
use App\UrlShort\Services\Validator;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Request;


class EncodeCommand implements CommandInterface
{

	private string|int|bool|null|float $link;

	public function __construct(
		protected Encode    $encode,
		protected DM        $record,
		protected Validator $validator,
	)
	{
	}

	public function runAction($data): array
	{
//		dd($data);
		$this->link = $data["url"];
		//валідує лінк
		$this->validator->link($this->link);
		//записує в бд
		return $this->save($data);
	}

	/**
	 * @throws ORMException
	 */
	protected function save($data): array
	{
		$this->encode->setLength($data['length'] ?? 8);
		$codeShort = $this->createArr($this->encode->encode($this->link), $this->link);
		$this->record->saveToDb($codeShort);
		return $codeShort;
	}

	protected function createArr(string $code, string $url): array
	{
		return [
			'code' => $code,
			'url' => $url,
		];
	}
}
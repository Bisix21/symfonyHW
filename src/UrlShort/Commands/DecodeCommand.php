<?php

namespace App\UrlShort\Commands;

use App\UrlShort\Decode;
use App\UrlShort\Interface\CommandInterface;
use App\UrlShort\Repository\DM;
use App\UrlShort\Services\Validator;
use Doctrine\ORM\Exception\NotSupported;


class DecodeCommand implements CommandInterface
{
	/**
	 * @param Decode $decoder
	 * @param DM $record
	 * @param Validator $validator
	 */
	public function __construct(
		protected Decode    $decoder,
		protected DM        $record,
		protected Validator $validator
	)
	{
	}

	/**
	 * @throws NotSupported
	 */
	public function runAction(string $data): string
	{
		$this->issetCodeInDB($data);
		return $this->decoder->decode($data);
	}

	/**
	 * @throws NotSupported
	 */
	protected function issetCodeInDB($data): void
	{
		$this->validator->isEmpty($this->record->read($data));
	}
}
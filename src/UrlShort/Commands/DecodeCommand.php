<?php

namespace App\UrlShort\Commands;

use App\UrlShort\Decode;
use App\UrlShort\Interface\CommandInterface;
use App\UrlShort\Repository\DM;
use App\UrlShort\Services\Validator;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Component\HttpFoundation\Request;


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
	public function runAction(Request $request): string
	{
		$this->issetCodeInDB($request);
		return $this->decoder->decode($request->request->get('code'));
	}

	/**
	 * @throws NotSupported
	 */
	protected function issetCodeInDB(Request $request): void
	{
		$this->validator->isEmpty($this->record->read($request->request->get('code')));
	}
}
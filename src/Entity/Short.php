<?php

namespace App\Entity;

use App\Repository\ShortRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShortRepository::class)]
class Short
{
	#[ORM\Id]
	#[ORM\Column(type: Types::INTEGER)]
	#[ORM\GeneratedValue]
	private int $id;
	#[ORM\Column(type: Types::TEXT, nullable: false)]
	private string $code;
	#[ORM\Column(type: Types::TEXT, nullable: false)]
	private string $url;
	#[ORM\Column(type: Types::INTEGER, nullable: true, options: ['default' => "0"])]
	private int $counter = 0;

	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl(string $url): void
	{
		$this->url = $url;
	}

	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 */
	public function setCode(string $code): void
	{
		$this->code = $code;
	}

	/**
	 * @return int
	 */
	public function getCounter(): int
	{
		return $this->counter;
	}

	public function incrementCounter(): void
	{
		$this->counter++;
	}
}
<?php

namespace App\Controller;

use App\UrlShort\Commands\DecodeCommand;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DecodeController extends AbstractController
{
	protected string $data = "";

	/**
	 * @return string|null
	 */
	public function getData(): ?string
	{
		return $this->data;
	}

	/**
	 * @param string|null $data
	 */
	public function setData(?string $data): void
	{
		$this->data = $data;
	}

	#[Route('/deshort', name: 'app_decode')]
	public function index(): Response
	{
		return $this->render('decode/index.html.twig', [
			'data' => $this->getData()?? "",
		]);
	}

	/**
	 * @throws NotSupported
	 */
	#[Route('/short/decode', methods: "POST")]
	public function decode(Request $request, DecodeCommand $decode): JsonResponse
	{
		$this->setData($decode->runAction($request));
		return $this->json($this->getData(), 200, ["Content-type" => "application/json"]);
	}
}

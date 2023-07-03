<?php

namespace App\Controller;

use App\UrlShort\Commands\DecodeCommand;
use App\UrlShort\Commands\EncodeCommand;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShortenerController extends AbstractController
{
	/**
	 * @var array|string
	 *
	 */
	private array|string $data = [];

	#[Route('/', name: 'app_shortener')]
	public function index(): Response
	{
		return $this->render('shortener/index.html.twig');
	}

	#[Route('/encode', methods: "POST")]
	public function encode(Request $request, EncodeCommand $encode): JsonResponse
	{
		$this->data = $encode->runAction($this->prepareDataFromRequest($request));
		return $this->json($this->data, 200, ["Content-Type" => "application/json"]);
	}

	/**
	 * @throws NotSupported
	 */
	#[Route('/decode', methods: "POST")]
	public function decode(Request $request, DecodeCommand $decode): JsonResponse
	{
		$this->setData($decode->runAction($request->request->get('code')));
		return $this->json($this->getData(), 200, ["Content-type" => "application/json"]);
	}

	/**
	 * @return array
	 */
	public function getData(): string|array
	{
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function setData(string|array $data): void
	{
		$this->data = $data;
	}

	protected function prepareDataFromRequest(Request $request): array
	{
		return [
		'url' => $request->request->get('url'),
		'length' => $request->request->get('length'),
		];
	}
}

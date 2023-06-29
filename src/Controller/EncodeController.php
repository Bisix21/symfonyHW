<?php

namespace App\Controller;

use App\UrlShort\Commands\EncodeCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/short', name: 'app_encode')]
class EncodeController extends AbstractController
{
	/**
	 * @var array
	 *
	 */
	private array $data = [];

	#[Route('', name: 'app_encode')]
	public function index(): Response
	{
		return $this->render('encode/index.html.twig', [
			'data' => $this->data
		]);
	}

	#[Route('/encode', methods: "POST")]
	public function encode(Request $request, EncodeCommand $encode): JsonResponse
	{
		$this->data = $encode->runAction($request);
		return $this->json($this->data, 200, ["Content-Type" => "application/json"]);
	}
}

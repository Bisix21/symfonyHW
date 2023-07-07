<?php

namespace App\Controller;

use App\Entity\Short;
use App\Repository\ShortRepository;
use App\Service\ShortenerService;
use App\UrlShort\Commands\DecodeCommand;
use App\UrlShort\Commands\EncodeCommand;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

	#[Route('/urls', name: '_all_urls')]
	public function urlsList(ShortRepository $shortRepository): Response
	{
		$allSites = $shortRepository->findAll();
		return $this->render('shortener/list_of_sites.html.twig', [
			'allSites' => $allSites
		]);
	}

	#[Route('/encode', name: "_encode_page")]
	public function encodePage(): Response
	{
		return $this->render('shortener/encode.html.twig');
	}

	#[Route('/decode', name: "_decode_page")]
	public function decodePage(): Response
	{
		return $this->render('shortener/decode.html.twig');
	}

	#[Route('/encode_form', name: "_encode_form", methods: "POST")]
	public function encode(Request $request, EncodeCommand $encode): Response
	{
		$this->data = $encode->runAction($this->prepareDataFromRequest($request));
		return $this->redirectToRoute('_all_urls');
//		return $this->json($this->data, 200, ["Content-Type" => "application/json"]);
	}

	protected function prepareDataFromRequest(Request $request): array
	{
		return [
			'url' => $request->request->get('url'),
			'length' => $request->request->get('length'),
		];
	}

	/**
	 * @throws NotSupported
	 */
	#[Route('/decode_form', name: "_decode_form", methods: "POST")]
	public function decode(Request $request, DecodeCommand $decode): JsonResponse
	{
		$this->setData($decode->runAction($request->request->get('code')));
		return $this->json($this->getData(), 200, ["Content-type" => "application/json"]);
	}

	/**
	 * @return string|array
	 */
	public function getData(): string|array
	{
		return $this->data;
	}

	/**
	 * @param string|array $data
	 */
	public function setData(string|array $data): void
	{
		$this->data = $data;
	}

	#[Route('/r/{code}', name: "_redirect", requirements: ['code' => "\w{3,10}"])]
	public function redirectAction(Short $short, ShortenerService $shortenerService): RedirectResponse
	{
		$url = $short->getUrl();
		$shortenerService->incrementCount($short);
		//TODO change '/' to $url for redirect to site encoded in code
		return $this->redirect('/');
	}
}

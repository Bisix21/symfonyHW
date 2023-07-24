<?php

namespace App\Controller;

use App\Entity\CodeUrlPair;
use App\Enum\RolesEnum;
use App\Repository\CodeUrlPairRepository;
use App\Repository\UserRepository;
use App\Service\ShortenerService;
use App\UrlShort\Commands\DecodeCommand;
use App\UrlShort\Commands\EncodeCommand;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShortenerController extends AbstractController
{
	#[Route('/', name: 'app_shortener')]
	public function index(): Response
	{
		return $this->render('shortener/index.html.twig');
	}

	#[Route('/urls', name: '_all_urls')]
	public function urlsList(CodeUrlPairRepository $codeUrlPair, UserRepository $userRepository): Response
	{
		if ($this->isGranted(RolesEnum::Admin)) {
			$allSites = $codeUrlPair->findAll();
		}else{
			$allSites = $codeUrlPair->findBy(['userId' => $this->getUser()->getUserIdentifier()]);
		}
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
		$encode->runAction($this->prepareDataFromRequest($request));
		return $this->redirectToRoute('_all_urls');
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
	public function decode(Request $request, DecodeCommand $decode): Response
	{
		return $this->render('shortener/_decode_result.html.twig', [
			'url' => $decode->runAction($request->request->get('code'))
		]);
	}

	#[Route('/r/{code}', name: "_redirect", requirements: ['code' => "\w{3,10}"])]
	public function redirectAction(CodeUrlPair $codeUrlPair, ShortenerService $shortenerService): RedirectResponse
	{
		$url = $codeUrlPair->getUrl();
		$shortenerService->incrementCount($codeUrlPair);
		// change '_all_urls' to $url for redirect to site encoded in code
		return $this->redirectToRoute('_all_urls');
	}
}

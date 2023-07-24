<?php

namespace App\Controller;

use App\Entity\CodeUrlPair;
use App\Enum\RolesEnum;
use App\Form\DecodeFormType;
use App\Form\EncodeFormType;
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
		} else {
			$allSites = $codeUrlPair->findBy(['userId' => $this->getUser()->getUserIdentifier()]);
		}
		return $this->render('shortener/list_of_sites.html.twig', [
			'allSites' => $allSites
		]);
	}

	#[Route('/encode', name: "_encode_page", methods: ["GET", "POST"])]
	public function encode(Request $request, EncodeCommand $encode): Response
	{
		$urlCodePair = new CodeUrlPair();
		$form = $this->createForm(EncodeFormType::class, $urlCodePair);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$encode->runAction($form->get('url')->getData());
			return $this->redirectToRoute('_all_urls');
		}

		return $this->render('shortener/encode.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @throws NotSupported
	 */
	#[Route('/decode', name: "_decode_page", methods: ["GET", "POST"])]
	public function decode(DecodeCommand $decode, Request $request): Response
	{
		$form = $this->createForm(DecodeFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			return $this->render('shortener/_decode_result.html.twig', [
				'url' => $decode->runAction($form->get('code')->getData())
			]);
		}
		return $this->render('shortener/decode.html.twig', [
			'form' => $form->createView()
		]);
	}

	#[Route('/r/{code}', name: "_redirect", requirements: ['code' => "\w{8}"])]
	public function redirectAction(CodeUrlPair $codeUrlPair, ShortenerService $shortenerService): RedirectResponse
	{
		$url = $codeUrlPair->getUrl();
		$shortenerService->incrementCount($codeUrlPair);
		// change '_all_urls' to $url for redirect to site encoded in code
		return $this->redirectToRoute('_all_urls');
	}
}

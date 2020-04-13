<?php

namespace App\Controller;

use App\Repository\ConferenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\{LoaderError, RuntimeError, SyntaxError};

/**
 * ConferenceController
 * @author    Gigabyte Software Limited
 * @copyright Gigabyte Software Limited
 */
class ConferenceController // By not extending AbstractController you can customise the controller more and only inject what you need
{
    private Environment $twig;
    /**
     * @var ConferenceRepository
     */
    private ConferenceRepository $conferenceRepository;

    public function __construct(Environment $twig, ConferenceRepository $conferenceRepository)
    {
        $this->twig = $twig;
        $this->conferenceRepository = $conferenceRepository;
    }

    /**
     * @Route("/", name="homepage", methods={"GET"})
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(): Response
    {
        $conferences = $this->conferenceRepository->findAll();

        return new Response($this->twig->render(
            'conference/index.html.twig',
            [
                'conferences' => $conferences,
            ]
        ));
    }

    /**
     * @Route("/conference/{id}", name="conference", methods={"GET"})
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show($id)
    {
        $conference = $this->conferenceRepository->findOneBy(['id' => $id]);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $conference->getComments(),
        ]));
    }
}

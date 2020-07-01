<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * ConferenceController
 * @author    Gigabyte Software Limited
 * @copyright Gigabyte Software Limited
 */
class ConferenceController // By not extending AbstractController you can customise the controller more and only inject what you need
{
    private Environment $twig;
    private ConferenceRepository $conferenceRepository;

    public function __construct(Environment $twig, ConferenceRepository $conferenceRepository)
    {
        $this->twig = $twig;
        $this->conferenceRepository = $conferenceRepository;
    }

    /**
     * @Route("/", name="homepage", methods={"GET"})
     * @return Response
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
     * The id being passed from the route is being used by this class to retrieve the @see Conference entity with that id
     *
     * @Route("/conference/{id}", name="conference", methods={"GET"})
     * @return Response
     */
    public function show(
        Request $request,
        Conference $conference,
        CommentRepository $commentRepository,
        ConferenceRepository $conferenceRepository
    ) {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'conferences' => $conferenceRepository->findAll(),
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
        ]));
    }
}

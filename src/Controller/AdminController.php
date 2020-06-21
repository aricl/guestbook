<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * AdminController
 * @author    Gigabyte Software Limited
 * @copyright Gigabyte Software Limited
 */
class AdminController
{
    /**
     * @var Environment
     */
    private Environment $twig;
    /**
     * @var ConferenceRepository
     */
    private ConferenceRepository $conferenceRepository;

    /**
     * AdminController constructor.
     */
    public function __construct(Environment $twig, ConferenceRepository $conferenceRepository)
    {
        $this->twig = $twig;
        $this->conferenceRepository = $conferenceRepository;
    }

    /**
     * @Route("/admin", name="admin_homepage", methods={"GET"})
     */
    public function index()
    {
        return new RedirectResponse('/admin/conferences', 302);
    }

    /**
     * @Route("/admin/conferences", name="admin_conferences", methods={"GET"})
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function conferences()
    {
        $conferences = $this->conferenceRepository->findAll();

        return new Response($this->twig->render(
            'admin/conferences.html.twig',
            [
                'conferences' => $conferences,
            ]
        ));
    }

    /**
     * @Route("/admin/conferences", name="admin_create_conference", methods={"POST"})
     */
    public function createConference(): Response
    {
        $city = strval($_POST['cityField']);
        $international = boolval($_POST['internationalField']);
        $year = intval($_POST['yearField']);

        $conference = new Conference($city, $international, $year);
        $this->conferenceRepository->save($conference);

        return new RedirectResponse('/admin/conferences', 302);
    }

    /**
     * @Route("/admin/conferences", name="admin_update_conference", methods={"PUT"})
     */
    public function updateConference(): Response
    {
        $conferenceId = $_POST['conferenceId'];
        $conference = $this->conferenceRepository->getByIdOrFail($conferenceId);

        $city = strval($_POST['cityField']);
        $conference->updateCity($city);
        $international = boolval($_POST['internationalField']);
        $conference->updateInternational($international);
        $year = intval($_POST['yearField']);
        $conference->updateYear($year);

        $this->conferenceRepository->save($conference);

        return new RedirectResponse('/admin/conferences', 302);
    }

    /**
     * @Route("/admin/conferences/{id}", name="admin_get_conference", methods={"GET"})
     */
    public function getConference(string $id): Response
    {
        $conference = $this->conferenceRepository->getByIdOrFail($id);

        return new Response(json_encode([
            'id' => $conference->getId(),
            'city' => $conference->getCity(),
            'year' => $conference->getYear(),
            'international' => $conference->isInternational(),
        ]));
    }
}

<?php

namespace App\Controller;

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

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="homepage")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(): Response
    {
        return new Response($this->twig->render(
            'conference/index.html.twig',
            [
                'name' => 'Adam',
            ]
        ));
    }

    /**
     * @Route("/conference", name="conference")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function conference()
    {
        return new Response($this->twig->render(
            'conference/index.html.twig',
            [
                'name' => 'Adam',
            ]
        ));
    }

    /**
     * @Route("/comment", name="comment")
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function comment()
    {
        return new Response($this->twig->render(
            'conference/index.html.twig',
            [
                'name' => 'Adam',
            ]
        ));
    }
}

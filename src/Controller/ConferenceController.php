<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
     * @Route("/conference/{name}", name="conference", methods={"GET"})
     * @param string $name
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(string $name): Response
    {
        return new Response($this->twig->render(
            'conference/index.html.twig',
            [
                'name' => $name,
            ]
        ));
    }
}

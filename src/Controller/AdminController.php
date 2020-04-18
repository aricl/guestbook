<?php

namespace App\Controller;

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
     * AdminController constructor.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
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
        return new Response($this->twig->render(
            'admin/conferences.html.twig',
            [
                'conferences' => [],
            ]
        ));
    }
}

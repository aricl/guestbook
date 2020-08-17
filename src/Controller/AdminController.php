<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

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
    public function __construct(
        Environment $twig,
        ConferenceRepository $conferenceRepository
    ) {
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
     */
    public function conferences()
    {
        return new Response($this->twig->render('admin/conferences.html.twig'));
    }

    /**
     * @Route("/admin/create_conference", name="admin_create_conference", methods={"GET", "POST"})
     */
    public function createConference(Request $request, FormFactoryInterface $formFactory): Response
    {
        $form = $formFactory
            ->create()
            ->add('city', TextType::class)
            ->add('international', CheckboxType::class, ['required' => false])
            ->add('year', IntegerType::class)
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $formData = $form->getData();

            $conference = new Conference($formData['city'], $formData['international'], $formData['year']);
            $this->conferenceRepository->save($conference);

            return new RedirectResponse('/admin/conferences', 302);
        }

        return new Response($this->twig->render(
            'admin/conference_form.html.twig',
            ['form' => $form->createView()]
        ));
    }

    /**
     * @Route("/admin/update_conference/{id}", name="admin_update_conference", methods={"GET", "POST"})
     */
    public function updateConference($id, Request $request, FormFactoryInterface $formFactory): Response
    {
        $conference = $this->conferenceRepository->getByIdOrFail($id);
        $form = $formFactory
            ->create()
            ->setData([
                'city' => $conference->getCity(),
                'international' => $conference->isInternational(),
                'year' => $conference->getYear(),
            ])
            ->add('city', TextType::class)
            ->add('international', CheckboxType::class, ['required' => false])
            ->add('year', IntegerType::class)
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $formData = $form->getData();

            $conference->updateCity($formData['city']);
            $conference->updateInternational($formData['international']);
            $conference->updateYear($formData['year']);
            $this->conferenceRepository->save($conference);

            return new RedirectResponse('/admin/conferences', 302);
        }

        return new Response($this->twig->render(
            'admin/conference_form.html.twig',
            ['form' => $form->createView()]
        ));
    }
}

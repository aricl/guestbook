<?php

namespace App\Controller;

use App\Api\SpamChecker;
use App\Entity\Comment;
use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Twig\Environment;

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
        return new Response($this->twig->render('conference/index.html.twig'));
    }

    /**
     * The id being passed from the route is being used by this class to retrieve the @see Conference entity with that id
     *
     * @Route("/conference/{slug}", name="conference", methods={"GET", "POST"})
     * @return Response
     */
    public function show(
        Request $request,
        Conference $conference,
        CommentRepository $commentRepository,
        FormFactoryInterface $formFactory,
        string $photoDirectory,
        SpamChecker $spamChecker
    ) {
        $form = $formFactory
            ->create()
            ->add('text', TextareaType::class)
            ->add('author', TextType::class)
            ->add('emailAddress', TextType::class)
            ->add('photo', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '1024k'])
                ],
            ])
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $formData = $form->getData();

            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($photoDirectory, $filename);
                } catch (FileException $exception) {
                    // Unable to upload photo, give up
                }

                $comment = $conference->addCommentWithPhoto(
                    $formData['text'],
                    $formData['author'],
                    $formData['emailAddress'],
                    $filename
                );

                $this->checkForSpam($request, $spamChecker, $comment);

                $this->conferenceRepository->save($conference);
            } else {
                $comment = $conference->addCommentWithoutPhoto(
                    $formData['text'],
                    $formData['author'],
                    $formData['emailAddress']
                );

                $this->checkForSpam($request, $spamChecker, $comment);

                $this->conferenceRepository->save($conference);
            }

            return new RedirectResponse('/conference/' . $conference->getSlug());
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'form' => $form->createView(),
        ]));
    }

    private function checkForSpam(Request $request, SpamChecker $spamChecker, Comment $comment): void
    {
        $context = $this->getContext($request);
        if ($spamChecker->getSpamScore($comment, $context) >= 1) {
            throw new \RuntimeException('Blatant spam, go away!');
        }
    }

    private function getContext(Request $request): array
    {
        return [
            'user_ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('user-agent'),
            'referrer' => $request->headers->get('referer'),
            'permalink' => $request->getUri(),
        ];
    }
}

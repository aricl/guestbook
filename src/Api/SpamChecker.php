<?php

namespace App\Api;

use App\Entity\Comment;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamChecker
{
    private HttpClientInterface $httpClient;
    private $endpoint;

    public function __construct(HttpClientInterface $httpClient, string $akismetKey)
    {
        $this->httpClient = $httpClient;
        $this->endpoint = sprintf('https://%s.rest.akismet.com/1.1/comment-check', $akismetKey);
    }

    /**
     * @return integer Spam score: 0 - not spam, 1 - maybe spam, 2 - blatant spam
     */
    public function getSpamScore(Comment $comment, array $context): int
    {
        $response = $this->httpClient->request(
            'POST',
            $this->endpoint,
            [
                'body' => array_merge(
                    $context,
                    [
                        'blog' => 'https://guestbook.example.com',
                        'comment_type' => 'comment',
                        'comment_author' => $comment->getAuthor(),
                        'comment_author_email' => 'akismet-guaranteed-spam@example.com',
                        'comment_content' => $comment->getText(),
                        'comment_date_gmt' => $comment->getCreatedAt()->format('c'),
                        'blog_lang' => 'en',
                        'blog_charset' => 'UTF-8',
                        'is_test' => true,
                    ]
                )
            ]
        );

        $headers = $response->getHeaders();
        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 2;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf(
                'Unable to check for spam: %s (%s).',
                $content,
                $headers['x-akismet-debug-help'][0]
            ));
        }

        return $content === 'true' ? 1 : 0;
    }
}

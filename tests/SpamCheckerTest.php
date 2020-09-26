<?php

namespace App\Tests;

use App\Api\SpamChecker;
use App\Entity\Comment;
use App\Entity\Conference;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class SpamCheckerTest extends TestCase
{
    public function testGetSpamScore()
    {
        $spamChecker = new SpamChecker(new MockHttpClient(), 'not a real akismet key. No, not by a long shot');

        $conference = new Conference('Helsinki', true, 2020);
        $comment = Comment::createWithoutPhoto(
            $conference,
            'Santeri',
            'Some comment or other',
            'fake.email@example.com'
        );
        $spamScore = $spamChecker->getSpamScore($comment, []);

        $this->assertEquals(0, $spamScore);
    }
}

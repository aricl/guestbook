<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(2, $crawler->filter('h4'));

        $client->clickLink('View');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Amsterdam 2019 Conference');
    }

    public function testCommentSubmission()
    {
        $client = static::createClient();
        $client->request('GET', 'conference/amsterdam-2019');
        $client->submitForm('Submit',
            [
                'form[author]' => 'Matt Cousins',
                'form[text]' => 'Upside-down with Matt',
                'form[emailAddress]' => 'ring@lock.com',
                'form[photo]' => dirname(__DIR__, 2) . '/public/images/under-construction.gif',
            ]
        );
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("There are 2 comments")');
    }
}

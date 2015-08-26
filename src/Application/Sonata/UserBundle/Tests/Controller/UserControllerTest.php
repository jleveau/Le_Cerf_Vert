<?php

namespace Application\Sonata\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testProfilearticles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profileArticles');
    }

}

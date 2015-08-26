<?php

namespace Application\Sonata\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerControllerTest extends WebTestCase
{
    public function testProfilearticles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profileArticles');
    }

}

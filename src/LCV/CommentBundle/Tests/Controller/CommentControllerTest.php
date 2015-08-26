<?php

namespace LCV\CommentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'show_comment');
    }

    public function testWrite()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'write_comment');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'delete_comment');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'edit_comment');
    }

}

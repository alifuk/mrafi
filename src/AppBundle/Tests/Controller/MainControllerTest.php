<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testLandingpage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

}

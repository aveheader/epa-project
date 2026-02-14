<?php

namespace Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderPageAccessTest extends WebTestCase
{
    public function testRedirectToLogin(): void
    {
        $client = static::createClient();
        $client->followRedirects(false);

        $client->request('GET', '/order');

        $this->assertResponseRedirects('/login');

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('h1', 'Необходима авторизация');
    }
}

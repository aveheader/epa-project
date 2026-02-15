<?php

namespace Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderPageAccessTest extends WebTestCase
{
    public function testGuestCannotAccessOrderPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/order');

        $this->assertResponseStatusCodeSame(403);
        $this->assertSelectorTextContains('h1', 'Доступ запрещён');
        $this->assertSelectorExists('a[href="/login"]');
    }
}

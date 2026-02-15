<?php

namespace Functional;

use App\Tests\Helpers\UserHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderFormTest extends WebTestCase
{
    public function testUserCanSeeOrderForm(): void
    {
        $client = static::createClient();
        $user = UserHelper::ensureUser($this->getContainer());
        $client->loginUser($user);

        $client->request('GET', '/order');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Создание заказа');

        $this->assertSelectorExists('select[name="order_form[serviceId]"]');
        $this->assertSelectorExists('input[name="order_form[email]"]');

        $this->assertSelectorExists('button[type="submit"]');
        $this->assertSelectorTextContains('button[type="submit"]', 'Подтвердить');

        $this->assertSelectorExists('#price');
    }
}

<?php

namespace Functional;

use Doctrine\ORM\EntityManagerInterface;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OrderFormTest extends WebTestCase
{
    public function testUserCanSeeOrderForm(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $userFactory = new UserFactory(
            $container->get(EntityManagerInterface::class),
            $container->get(UserPasswordHasherInterface::class)
        );

        $user = $userFactory->create();

        $client->loginUser($user);

        $client->request('GET', '/order');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Создать заказ');

        $this->assertSelectorExists('select[name="order_form[service]"]');
        $this->assertSelectorExists('input[name="order_form[email]"]');

        $this->assertSelectorExists('button');
        $this->assertSelectorTextContains('button', 'Отправить');
    }
}

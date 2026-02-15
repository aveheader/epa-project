<?php

namespace Functional;

use App\Entity\Order;
use App\Tests\Helpers\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderFormValidatorTest extends WebTestCase
{
    public function testUserCanCreateOrder(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $repo = $entityManager->getRepository(Order::class);
        $user = UserHelper::ensureUser($container);
        $client->loginUser($user);

        $before = $repo->count([]);

        $crawler = $client->request('GET', '/order');
        $this->assertResponseIsSuccessful();

        $email = 'order_' . uniqid() . '@test.ru';

        $form = $crawler->selectButton('Подтвердить')->form([
            'order_form[serviceId]' => 'business',
            'order_form[email]' => $email,
        ]);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Заказ успешно сформирован');

        $after = $repo->count([]);
        $this->assertSame($before + 1, $after);

        /** @var Order|null $order */
        $order = $repo->findOneBy(['email' => $email]);
        $this->assertNotNull($order);
        $this->assertSame('business', $order->getServiceId());
        $this->assertSame($user->getId(), $order->getUser()->getId());
    }

    public function testCreateOrderWithoutEmail(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $user = UserHelper::ensureUser($container);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/order');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Подтвердить')->form([
            'order_form[serviceId]' => 'business',
            'order_form[email]' => '',
        ]);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Поле email не может быть пустым');
    }

    public function testCreateOrderWithoutService(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $user = UserHelper::ensureUser($container);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/order');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Подтвердить')->form([
            'order_form[serviceId]' => '',
            'order_form[email]' => 'test@test.ru',
        ]);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Выберите сервис');
    }
}

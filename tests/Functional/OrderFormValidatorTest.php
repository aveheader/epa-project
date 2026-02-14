<?php

namespace Functional;

use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OrderFormValidatorTest extends WebTestCase
{
    private function getClientAndContainerAndLoginUser(): array
    {
        $client = static::createClient();
        $container = static::getContainer();

        $userFactory = new UserFactory(
            $container->get(EntityManagerInterface::class),
            $container->get(UserPasswordHasherInterface::class)
        );

        $user = $userFactory->create();

        $client->loginUser($user);

        return [$client, $container, $user];
    }

    private function getOrdersFilePath(ContainerInterface $container): string
    {
        return $_ENV['ORDERS_FILE_PATH']
            ?? ($container->getParameter('kernel.project_dir') . '/var/orders_test.jsonl');
    }

    public function testUserCanCreateOrder(): void
    {
        [$client, $container, $user] = $this->getClientAndContainerAndLoginUser();

        $filePath = $this->getOrdersFilePath($container);

        @unlink($filePath);

        $crawler = $client->request('GET', '/order');
        $this->assertResponseIsSuccessful();

        $email = 'order_' . uniqid() . '@test.ru';

        $form = $crawler->selectButton('Отправить')->form([
            'order_form[service]' => 'business',
            'order_form[email]' => $email,
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/order');
        $client->followRedirect();

        $this->assertFileExists($filePath);
        $content = trim((string) file_get_contents($filePath));

        $lines = explode("\n", $content);
        $lastLine = trim(end($lines));

        $order = json_decode($lastLine, true);

        $this->assertIsArray($order);
        $this->assertArrayHasKey('email', $order);
        $this->assertSame($email, $order['email']);
        $this->assertSame('business', $order['services']);
        $this->assertSame($user->getId(), $order['user_id']);
    }

    public function testCreateOrderWithoutEmail(): void
    {
        [$client, $container] = $this->getClientAndContainerAndLoginUser();

        $filePath = $this->getOrdersFilePath($container);

        @unlink($filePath);

        $crawler = $client->request('GET', '/order');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Отправить')->form([
            'order_form[service]' => 'business',
            'order_form[email]' => '',
        ]);

        $client->submit($form);

        $this->assertFalse($client->getResponse()->isRedirection());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Поле email не может быть пустым');
        $this->assertFileDoesNotExist($filePath);
    }

    public function testCreateOrderWithoutService(): void
    {
        [$client, $container] = $this->getClientAndContainerAndLoginUser();

        $filePath = $this->getOrdersFilePath($container);

        @unlink($filePath);

        $crawler = $client->request('GET', '/order');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Отправить')->form([
            'order_form[service]' => '',
            'order_form[email]' => 'test@test.ru',
        ]);

        $client->submit($form);

        $this->assertFalse($client->getResponse()->isRedirection());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Поле выбора сервиса не может быть пустым');
        $this->assertFileDoesNotExist($filePath);
    }
}

<?php

namespace App\Controller;

use App\DTO\OrderRequestDTO;
use App\Entity\User;
use App\Form\OrderFormType;
use App\Service\OrderCreator;
use App\Service\ServiceTypeProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order', name: 'app_order', methods: ['GET', 'POST'])]
final class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderCreator $orderCreator,
        private readonly ServiceTypeProvider $serviceTypeProvider,
    ) {}

    public function __invoke(Request $request): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return new Response(
                $this->renderView('security/forbidden.html.twig'),
                Response::HTTP_FORBIDDEN
            );
        }

        $dto = new OrderRequestDTO();
        $form = $this->createForm(OrderFormType::class, $dto);
        $form->handleRequest($request);

        $success = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $this->orderCreator->create($user, $dto);

            $dto = new OrderRequestDTO();
            $form = $this->createForm(OrderFormType::class, $dto);

            $success = true;
        }

        return $this->render('order/order.html.twig', [
            'form' => $form->createView(),
            'prices' => $this->serviceTypeProvider->getPricesMap(),
            'success' => $success,
        ]);
    }
}

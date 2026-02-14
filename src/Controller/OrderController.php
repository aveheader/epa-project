<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\ServiceType;
use App\Form\OrderFormType;
use App\Service\OrderCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order', name: 'app_order', methods: ['GET', 'POST'])]
#[IsGranted('ROLE_USER')]
final class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderCreator $orderCreator,
    ){
    }
    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(OrderFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $service = $data['service'];
            $email = $data['email'];

            $this->orderCreator->create($service, $email, $user);

            return $this->redirectToRoute('app_order');
        }

        $prices = [];
        foreach (ServiceType::cases() as $case) {
            $prices[$case->value] = $case->price();
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'prices' => $prices,
        ]);
    }
}

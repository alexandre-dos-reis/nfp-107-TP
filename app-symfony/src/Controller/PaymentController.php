<?php

namespace App\Controller;

use DateTime;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Purchase;
use App\Entity\Purchasedetail;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ProductRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment-checkout", name="payment_checkout")
     */
    public function checkout(CartService $cartService, UserRepository $userRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted(User::ROLE_CLIENT, $this->getUser())) {
            $this->addFlash('danger', 'You must have a client account in order to make a purchase !');
            return $this->redirectToRoute('login');
        }

        if ($cartService->countProducts() === 0) {
            $this->addFlash('danger', 'Your cart is empty !');
            return $this->redirectToRoute('products_index');
        }

        return $this->render('payment/index.html.twig', [
            'cartItems' => $cartService->getDetailedCartItems(),
            'total' => $cartService->getTotal(),
            'user' => $userRepo->findOneBy(['email' => $this->getUser()->getUserIdentifier()])
        ]);
    }

    /**
     * @Route("/payment-confirm", name="payment_confirm")
     */
    public function confirm(CartService $cartService, UserRepository $userRepo, EmployeeRepository $employeeRepo, EntityManagerInterface $em, TimeslotRepository $timeslotRepo, ProductRepository $productRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted(User::ROLE_CLIENT, $this->getUser())) {
            $this->addFlash('danger', 'You must have a client account in order to pay !');
            return $this->redirectToRoute('login');
        }

        if ($cartService->countProducts() === 0) {
            $this->addFlash('danger', 'Your cart is empty !');
            return $this->redirectToRoute('products_index');
        }

        $user = $userRepo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $faker = Factory::create();
        $employee = $faker->randomElement($employeeRepo->findAll());
        $timeSlot = $faker->randomElement($timeslotRepo->findAll());

        $purchase = (new Purchase)
            ->setAmount($cartService->getTotal())
            ->setDatecreation(new DateTime())
            ->setEmployee($employee)
            ->setItemsNumber($cartService->countProducts())
            ->setMissingNumber(0)
            ->setStatus(0)
            ->setTimeSlot($timeSlot)
            ->setToPay(200)
            ->setUser($user);
            
        $em->persist($purchase);

        foreach ($cartService->getDetailedCartItems() as $cartItem ) {
            
            // Stock checks
            $product = $productRepo->find($cartItem->product->getId());
            $newStock = $product->getStock() - $cartItem->qty;
            
            if($newStock < 0){
                $this->addFlash('danger', "The quantity requested for the #{$product->getId()} {$product->getName()} exceeds the available stock !");
                $this->redirectToRoute('cart_index');
            }
            
            $product->setStock($newStock);
            $em->persist($product);

            // We add the PurchaseDetail to the Purchase
            $orderDetail = (new Purchasedetail())
                ->setProduct($cartItem->product)
                ->setQuantity($cartItem->qty)
                ->setPurchase($purchase)
                ->setPrepared(0);
            $purchase->addOrderDetail($orderDetail);
            $em->persist($orderDetail);
        }

        $em->flush();     

        $this->addFlash('success', "Your payment has succeded, we are preparing your order.");
        $cartService->emptyCart();

        return $this->redirectToRoute('purchase_index');
    }
}

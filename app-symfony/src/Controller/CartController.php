<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Service\Flash\CartMessage;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends AbstractController
{
    private CartMessage $cartMessage;

    public function __construct(CartMessage $cartMessage)
    {
        $this->cartMessage = $cartMessage;
    }

    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartService->getDetailedCartItems(),
            'total' => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/carts-saved", name="cart_saved")
     */
    public function saved(BasketRepository $basketRepo, UserRepository $userRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isGranted(User::ROLE_CLIENT, $this->getUser())) {
            $user = $userRepo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $savedCarts = $basketRepo->findBy(['user' => $user]);
        } else {
            $this->addFlash('danger', 'You must have a client account to use this feature !');
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/saved.html.twig', [
            'savedCarts' => $savedCarts
        ]);
    }

    /**
     * @Route("/cart-remove-product/{id}", name="cart_remove_product")
     */
    public function removeProduct(int $id, CartService $cartService, ProductRepository $productRepo): Response
    {
        $product = $productRepo->find($id);

        if (!$product) throw new NotFoundHttpException("This product doesn't exist !");

        $cartService->removeProduct($product);
        $this->cartMessage->add($product, CartMessage::TYPE_SUCCESS, CartMessage::_REMOVED);

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart-update-qty/{id}", name="cart_update_qty")
     */
    public function updateQty(int $id, CartService $cartService, ProductRepository $productRepo, Request $request): Response
    {
        $product = $productRepo->find($id);

        if (!$product) throw new NotFoundHttpException("This product doesn't exist !");

        $newQty = $request->request->get('qty-' . $id);

        if (is_null($newQty)) throw new \Exception("Quantity can't be null !");

        $wasProductRemoved = $cartService->updateProductQty($product, (int)$newQty);
        $this->cartMessage->add($product, CartMessage::TYPE_SUCCESS, $wasProductRemoved ? CartMessage::_REMOVED : CartMessage::_UPDATED);

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart-increment-qty/{id}", name="cart_increment_qty")
     */
    public function incrementQty(int $id, CartService $cartService, ProductRepository $productRepo, Request $request): Response
    {
        $product = $productRepo->find($id);

        if (!$product) throw new NotFoundHttpException("This product doesn't exist !");

        $isNewProduct = $cartService->incrementProductQty($product);
        $this->cartMessage->add($product, CartMessage::TYPE_SUCCESS, $isNewProduct ? CartMessage::_NEW : CartMessage::_UPDATED);

        return $this->redirectToRoute('cart_index');
    }
}

<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function redirectToProducts(): Response
    {
        return $this->redirectToRoute('products_index');
    }

    /**
     * @Route("/products", name="products_index")
     */
    public function index(ProductRepository $productRepo): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepo->findBy([], ['section' => 'ASC'])
        ]);
    }
    /**
     * @Route("/products/{id}", name="product_detail")
     */
    public function detail(int $id, ProductRepository $productRepo): Response
    {
        $product = $productRepo->find($id);

        if (!$product) throw $this->createNotFoundException("This product doesn't exists !");

        return $this->render('product/detail.html.twig', [
            'p' => $product
        ]);
    }
}

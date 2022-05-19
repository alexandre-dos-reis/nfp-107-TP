<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
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

        if (!$product) throw $this->createNotFoundException("This purchase doesn't exists !");

        return $this->render('product/detail.html.twig', [
            'p' => $product
        ]);
    }
}

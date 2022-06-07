<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(ProductRepository $productRepo, Request $request): Response
    {
        if (empty($request->get('search'))) {
            $products = $productRepo->findBy([], ['section' => 'ASC']);
        } else {
            $products = $productRepo->fulltextSearch($request->get('search'));
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            // 'sections' => $sectionRepo->findAll()
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

<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index(PurchaseRepository $purchaseRepo): Response
    {
        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchaseRepo->findAll()
        ]);
    }
    /**
     * @Route("/purchases/{id}", name="purchase_details")
     */
    public function details(int $id, PurchaseRepository $purchaseRepo): Response
    {
        $purchase = $purchaseRepo->find($id);

        if (!$purchase) throw $this->createNotFoundException("This purchase doesn't exists !");

        return $this->render('purchase/details.html.twig', [
            'purchase' => $purchase
        ]);
    }
}

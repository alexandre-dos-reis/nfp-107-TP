<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Employee;
use App\Entity\Purchase;
use App\Repository\UserRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index(PurchaseRepository $purchaseRepo, UserRepository $userRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isGranted(User::ROLE_CLIENT, $this->getUser())) {
            $user = $userRepo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $purchases = $user->getPurchases();
        } else {
            $purchases = $purchaseRepo->findAll();
        }

        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchases
        ]);
    }

    /**
     * @Route("/purchases/{id}", name="purchase_details")
     */
    public function details(int $id, PurchaseRepository $purchaseRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $purchase = $purchaseRepo->find($id);

        if (!$purchase) throw $this->createNotFoundException("This purchase doesn't exists !");

        return $this->render('purchase/details.html.twig', [
            'purchase' => $purchase,
            'statuses' => Purchase::STATUSES
        ]);
    }

    /**
     * @Route("/purchases-update-status/{id}", name="purchase_update_status")
     */
    public function updateStatus(int $id, PurchaseRepository $purchaseRepo, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isGranted(Employee::ROLE_EMPLOYEE, $this->getUser())) {
            $purchase = $purchaseRepo->find($id);

            if (!$purchase) throw $this->createNotFoundException("This purchase doesn't exists !");

            $newStatus = $request->request->get('status');

            if (is_null($newStatus)) throw new \Exception("The status is mandatory !");

            $purchase->setStatus((int)$newStatus);
            $em->persist($purchase);
            $em->flush();

            $this->addFlash('success', "The status for the purchase #{$purchase->getId()} has passed to : {$purchase->getStatusLabel()}");

            return $this->render('purchase/details.html.twig', [
                'purchase' => $purchase,
                'statuses' => Purchase::STATUSES
            ]);
        }

        $this->addFlash('danger', "You don't have the permission to execute this action.");
        return $this->redirectToRoute('purchase_index');
    }
}

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
    public function index(PurchaseRepository $purchaseRepo, Request $request, UserRepository $userRepo): Response
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', "You have to be connected to view your purchases.");
            return $this->redirectToRoute('login');
        }
        /**
         * @var Purchase[] $purchases
         */
        $purchases = [];

        if ($this->isGranted(User::ROLE_CLIENT, $user)) {
            // dd('CLIENT');
            if (is_null($request->get('status'))) {
                $purchases = $purchaseRepo->findBy([
                    'user' => $user,
                ]);
            } else {
                $purchases = $purchaseRepo->findBy([
                    'status' => (int)$request->get('status'),
                    'user' => $user,
                ]);
            }
        }

        if ($this->isGranted(Employee::ROLE_EMPLOYEE, $user)) {
            if (is_null($request->get('status'))) {
                $purchases = $purchaseRepo->findAll();
            } else {
                $purchases = $purchaseRepo->findBy(['status' => (int)$request->get('status')]);
            }
        }

        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchases,
            'statuses' => Purchase::STATUSES,
            'filteredStatus' => $request->get('status') ?? null
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

            $newStatus = $request->get('status');

            if (is_null($newStatus)) throw new \Exception("The status is mandatory !");

            $purchase->setStatus((int)$newStatus);
            $em->persist($purchase);
            $em->flush();

            $this->addFlash('success', "The status for the purchase #{$purchase->getId()} has passed to : {$purchase->getStatusLabel()}");
            return $this->redirectToRoute('purchase_details', ['id' => $purchase->getId()]);
        }

        $this->addFlash('danger', "You don't have the permission to execute this action.");
        return $this->redirectToRoute('purchase_index');
    }
}

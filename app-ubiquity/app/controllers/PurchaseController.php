<?php

namespace controllers;

use models\Purchase;
use Ubiquity\orm\DAO;
use Ubiquity\utils\flash\FlashMessage;
use Ubiquity\utils\http\URequest;

/**
 * Controller PurchaseController
 */
class PurchaseController extends \controllers\ControllerBase
{

  /**
   * @route("/purchases", "name"=>"purchases.index")
   */
  public function index()
  {
    return $this->loadView('purchase/index.html.twig', [
      'purchases' => DAO::getAll(Purchase::class),
    ]);
  }

  /**
   * @route("/purchases/{id}", "name"=>"purchases.detail")
   */
  public function detail(int $id)
  {
    return $this->loadView('purchase/details.html.twig', [
      'purchase' => DAO::getOne(Purchase::class, $id, ['purchasedetails.product']),
      'statuses' => Purchase::STATUSES
    ]);
  }

  /**
   * @route("/purchases/updateStatus/{id}", "name"=>"purchases.update.status", "methods"=>["post"])
   */
  public function updateStatus(int $id)
  {

    $status = URequest::post('status');

    if (is_null($status)) {
      // $flash->setType('error');
      // $flash->setContent("The status can't be null !");
      return $this->redirectToRoute('purchases.index');
    }

    /**
     * @var Purchase $purchase
     */
    $purchase = DAO::getOne(Purchase::class, $id);

    if (!$purchase) {
      // $flash->setType('error');
      // $flash->setContent("This purchase doesn't exists !");
      return $this->redirectToRoute('purchases.index');
    }

    $purchase->setStatus((int)$status);


    if (DAO::save($purchase)) {
      // $flash->setType('success');
      // $flash->setContent("The status for {$purchase->getId()} was updated to {$purchase->getStatusLabel()}.");
    } else {
      // $flash->setType('error');
      // $flash->setContent("The status for {$purchase->getId()} wasn't changed ! There was a problem with the ORM / DB.");
    }

    return $this->redirectToRoute('purchases.detail', [$id]);
  }
}

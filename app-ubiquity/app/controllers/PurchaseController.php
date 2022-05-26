<?php

namespace controllers;

use models\Purchase;
use Ubiquity\orm\DAO;

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
      'purchases' => DAO::getAll(Purchase::class)
    ]);
  }

  /**
   * @route("/purchases/{id}", "name"=>"purchases.detail")
   */
  public function detail(int $id)
  {
    return $this->loadView('purchase/details.html.twig', [
      'purchase' => DAO::getOne(Purchase::class, $id)
    ]);
  }
}

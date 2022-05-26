<?php

namespace controllers;

use models\Product;
use Ubiquity\orm\DAO;


/**
 * Controller ProductController
 */
class ProductController extends ControllerBase
{
  /**
   * @route("/products", "name"=>"products.index")
   */
  public function index()
  {
    return $this->loadView('product/index.html.twig', [
      'products' => DAO::getAll(Product::class)
    ]);
  }

  /**
   * @route("/products/{id}", "name"=>"products.detail")
   */
  public function detail(int $id)
  {
    return $this->loadView('product/details.html.twig', [
      'p' => DAO::getOne(Product::class, $id)
    ]);
  }
}

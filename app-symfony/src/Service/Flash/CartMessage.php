<?php

namespace App\Service\Flash;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartMessage implements FlashMessageServiceInterface
{
    public const _NEW = "_NEW";
    public const _UPDATED = "_UPDATE";
    public const _REMOVED = "_REMOVED";

    private FlashBagInterface $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function add(object $product, string $type, string $action): void
    {
        if (!$product instanceof Product) {
            throw new \Exception("The object passed is not an instance of product");
        }

        $this->flashBag->add($type, $this->getMessages($product)[$action]);
    }

    public function getMessages(object $object): array
    {
        return [
            self::_NEW => "The {$object->getName()} has been added to your cart.",
            self::_UPDATED => "The quantity for the {$object->getName()} has been updated.",
            self::_REMOVED => "The {$object->getName()} has been deleted from your cart.",
        ];
    }
}

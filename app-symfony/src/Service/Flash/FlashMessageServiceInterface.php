<?php

namespace App\Service\Flash;

interface FlashMessageServiceInterface
{
    public const TYPE_SUCCESS = "success";
    public const TYPE_FAILURE = "danger";

    public function add(object $object, string $type, string $action): void;
    public function getMessages(object $object): array;
}

<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount(
        $value,
        string $currency = '€',
        string $decimalSep = ',',
        string $thounsandSep = ' '
    ): string {
        return number_format($value / 100, 2, $decimalSep, $thounsandSep) . ' ' . $currency;
    }
}

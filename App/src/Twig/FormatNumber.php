<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormatNumber extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('formatNumber', [$this, 'formatNumber']),
        ];
    }

    public function formatNumber(int $amount): string
    {
        return number_format($amount / 100, 2);
    }
}

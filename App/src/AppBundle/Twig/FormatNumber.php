<?php

namespace AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormatNumber extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('area', array($this, 'formatNumber')),
        );
    }

    public function formatNumber(int $amount)
    {
        return number_format($amount, 2);
    }
}

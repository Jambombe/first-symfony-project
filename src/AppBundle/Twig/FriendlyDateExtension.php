<?php

namespace AppBundle\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FriendlyDateExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('friendlydate', [$this, 'friendlyDate']),
        ];
    }

    public function friendlyDate($date){

        if (strtotime(date('d-m-Y')) === strtotime($date)){ // Si c'est aujourd'hui
            return "Aujourd'hui";
        } else {
            return "Le " . $date;
        }
    }

}
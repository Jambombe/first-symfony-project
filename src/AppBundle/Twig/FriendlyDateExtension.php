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

    public function friendlyDate(\DateTime $datetime){

//        if (strtotime(date('d-m-Y')) === strtotime($date)){ // Si c'est aujourd'hui
//            return "Aujourd'hui";
//        } else {
//            return "Le " . $date;
//        }
        $now = new \DateTime;

        if ($datetime->diff($now)->y === 0 && $datetime->diff($now)->m === 0 && $datetime->diff($now)->d === 0){
            return "Aujourd'hui";
        } else {
            return 'Le ' . $datetime->format('d-m-Y');
        }
    }

}
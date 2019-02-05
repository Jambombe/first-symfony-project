<?php

namespace AppBundle\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FriendlyDateExtension extends AbstractExtension
{

    private $numberToMonth = [
        1=>'Janvier',
        2=>'Février',
        3=>'Mars',
        4=>'Avril',
        5=>'Mai',
        6=>'Juin',
        7=>'Juillet',
        8=>'Août',
        9=>'Septembre',
        10=>'Octobre',
        11=>'Novembre',
        12=>'Décembre',
    ];



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
            $monthNumber = intval($datetime->format('m'));
            return 'Le ' . $datetime->format('d ') . $this->numberToMonth[$monthNumber] . $datetime->format(' Y');
        }
    }

}
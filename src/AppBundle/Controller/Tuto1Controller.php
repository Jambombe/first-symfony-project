<?php
// src/AppBundle/Controller/Tuto1Controller.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Tuto1Controller extends Controller
{
    // La route est accessible via /web/app_dev.php/tuto1/phrase
    /**
     * @Route("/tuto1/phrase")
     */
    public function phraseAction()
    {
        return new Response(
            '<html><body>Oui ?</body></html>'
        );
    }

    // La route est accessible via (navigateur) /web/app_dev.php/tuto1/template
    // Le template est ici : /app/Resources/views/tuto1/index.html.twig
    /**
     * @Route("/tuto1/template")
     */
    public function templateAction()
    {

        $phrase = "Je suis la phrase";

        return $this->render('tuto1/index.html.twig', [
            'phrase' => $phrase
        ]);

        // On peut ne pas passer de variable au template en faisant : return $this->render('tuto1/index.html.twig', []);
    }

    /**
     * @Route("/tuto1/list")
     */
    public function listAction(Request $request)
    {
        $students = $this->loadStudents();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $students, // donnees
            $request->query->getInt('page', 1), // num page lors du chargement
            3 // nb element par page
        );

        return $this->render('tuto1/list.html.twig', array('pagination' => $pagination));
    }

    private function loadStudents()
    {
        $array = array();

        array_push($array, array('prenom'=>'a', 'age'=> 1));
        array_push($array, array('prenom'=>'b', 'age'=> 2));
        array_push($array, array('prenom'=>'c', 'age'=> 3));
        array_push($array, array('prenom'=>'d', 'age'=> 4));
        array_push($array, array('prenom'=>'e', 'age'=> 5));
        array_push($array, array('prenom'=>'f', 'age'=> 6));
        array_push($array, array('prenom'=>'g', 'age'=> 7));
        array_push($array, array('prenom'=>'h', 'age'=> 8));
        array_push($array, array('prenom'=>'i', 'age'=> 9));
        array_push($array, array('prenom'=>'j', 'age'=> 10));
        array_push($array, array('prenom'=>'k', 'age'=> 11));

        return $array;
    }
}

?>
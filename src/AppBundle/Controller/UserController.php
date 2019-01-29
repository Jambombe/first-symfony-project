<?php
// src/AppBundle/Controller/UserController.php
namespace AppBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserController
 * @package AppBundle\Controller
 * Possible d'ajouter "@Route('/user')" pour préfixer chaque route des actions de la classe par '/user'
 */
class UserController extends Controller
{
    // La route est accessible via /web/app_dev.php/user/phrase
//    /**
//     * @Route("/user/phrase")
//     */
//    public function phraseAction()
//    {
//        return new Response(
//            '<html><body>Oui ?</body></html>'
//        );
//    }

    // La route est accessible via (navigateur) /web/app_dev.php/user/template
    // Le template est ici : /app/Resources/views/user/index.html.twig
//    /**
//     * @Route("/user/template")
//     */
//    public function templateAction()
//    {
//
//        $phrase = "Je suis la phrase";
//
//        return $this->render('user/index.html.twig', [
//            'phrase' => $phrase
//        ]);
//
//        // On peut ne pas passer de variable au template en faisant : return $this->render('user/index.html.twig', []);
//    }

    /**
     * @Route("/users/list")
     */
    public function listAction(Request $request, PaginatorInterface $paginator)
    {
//        $students = $this->loadStudents();
        $students = $this->loadStudentsV2();

//        $paginator = $this->get('knp_paginator'); // Autre solution : dans la signature de la methode
        $pagination = $paginator->paginate(
            $students, // donnees
            $request->query->getInt('page', 1), // num page lors du chargement
            3 // nb element par page
        );

        return $this->render('user/pages/list.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/user/{id}")
     */
    public function userProfileAction(Request $r){

        $userId = $r->get('id');

        $json = @file_get_contents(
            $this->generateUrl('api_user_by_id', ['id'=>$userId], UrlGeneratorInterface::ABSOLUTE_URL)
        ); // Permet d'avoir l'url à atteindre à partir de la route correspondante

        $user = json_decode($json, true);

        return $this->render(
            'user/pages/profile.html.twig',
            [
                'user' => $user,
            ]);

    }

    /**
     * @Route("/user/sendmail/{email}")
     */
    public function sendEmailAction(Request $r, \Swift_Mailer $mailer)
    {
        $email = $r->get('email'); // On recupère le parametre 'email' (defini dans la route)

        // Si on a pas une adresse email valide
        if (! filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $email = 'lucas.barneoudarnaud@gmail.com'; // adresse par defaut
        }

        if(!$r->isXmlHttpRequest()) {

            return $this->json(array('status' => 'error'), Response::HTTP_FORBIDDEN);
        }

        $message = (new \Swift_Message('mail test'))
            ->setFrom('cdw2k18@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'user/pages/email.html.twig',
                    ['email' => $email]
                ),
                'text/html'
            );

        $isSend = $mailer->send($message);

        return $this->json(
            [
                'status'=>'ok',
            ]
        );

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

    private function loadStudentsV2()
    {
        $json = file_get_contents(
            $this->generateUrl('api_users', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ); // Permet d'avoir l'url à atteindre à partir de la route correspondante

        $arr = json_decode($json);

        return $arr;
    }
}

?>
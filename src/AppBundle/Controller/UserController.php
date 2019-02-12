<?php
// src/AppBundle/Controller/UserController.php
namespace AppBundle\Controller;

use AppBundle\Entity\ProfileImage;
use AppBundle\Form\ProfileImageType;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/user/new")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function newAction(Request $request){

        date_default_timezone_set('Europe/Paris');

        $user= new User();
//        $user->setNom('');
//        $user->setPrenom('');
        $user->setBirthdate(new \DateTime());
//        $user->setEmail('');

        $newProfileImage = new ProfileImage();

        $form = $this->createForm(ProfileImageType::class, $newProfileImage);
        $form
//            ->add('url_image', TextType::class, [ 'label' => 'Image (URL)', 'attr'=>['placeholder' => 'Lien image'], "mapped"=>false])
            ->add('submit', SubmitType::class)
        ;

//        $form = $this->createFormBuilder($user)
//            ->add('nom', TextType::class, [ 'attr'=>['placeholder' => 'Nom']])
//            ->add('prenom', TextType::class, [ 'attr'=>['placeholder' => 'Prénom']])
//            ->add('birthdate', DateType::class, [ 'label' => 'Date de naissance'])
//            ->add('email', EmailType::class, [ 'attr'=>['placeholder' => 'Adresse email']])
//            ->add('url_image', TextType::class, [ 'label' => 'Image (URL)', 'attr'=>['placeholder' => 'Lien image'], "mapped"=>false])
//            ->add('save', SubmitType::class, ['label' => "Créer l'utilisateur"])
//            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user->setRegistrationDate(new \DateTime());

//            $urlImage = $form['url_image']->getData();

//            if ($urlImage)
//            {
//                $img = new ProfileImage();
//                $img->setUrl($urlImage)
//                    ->setUser($user);
//
//                $user->addProfileImage($img);
//            }

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newProfileImage);
//                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('notice', "C'est enregistré !");
//                echo '<script>setTimeout(function(){ swal("Utilisateur créé avec succès !" ,"", "success"); }, 500);</script>';
            } catch (\Exception $e) {
                dump($e->getMessage());
                echo '<script>setTimeout(function(){ swal("Une erreur est survenue lors de l\'envoi :/" ,"", "error"); }, 500);</script>';
            }
        }

        return $this->render('user/pages/inscription.html.twig',
            [
               'form'=> $form->createView(),
            ]
        );

    }

    /**
     * @Route("/users/list", name="users_list")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Exception
     */
//    public function listAction(Request $request, PaginatorInterface $paginator, UserService $userService)
    public function listAction(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em)
    {
        $minDate = $request->query->get('minDate');
        $maxDate = $request->query->get('maxDate');

        if ($minDate && $maxDate)
        {
            $minDate = (new \DateTime($minDate));
            $maxDate = (new \DateTime($maxDate));
            dump($minDate, $maxDate);

//            $students = $em
//                ->createQuery("SELECT u FROM AppBundle:User u WHERE u.registrationDate >= :minDate AND u.registrationDate <= :maxDate")
//                ->setParameters(array('minDate'=>$minDate, 'maxDate'=>$maxDate))
//                ->getResult();
            //////////////////////////////////
            $students = $em->getRepository(User::class)->findByCreationDates($minDate, $maxDate);
        } else {
//          $students = $this->loadStudents();
//          $students = $this->loadStudentsV2();
            $students = $this->loadStudentsV3();
        }

        /* @var UserService */
        $userService = $this->get('user_service');

//        $paginator = $this->get('knp_paginator'); // Autre solution : dans la signature de la methode
        $pagination = $paginator->paginate(
            $students, // donnees
            $request->query->getInt('page', 1), // num page lors du chargement
            10 // nb element par page
        );

        $allAges = [];
        foreach ($students as $s){
            array_push($allAges, $s->getAge());
        }

        return $this->render(
            'user/pages/list.html.twig',
            [
                'pagination' => $pagination,
                'moyenne_age' => $userService->moyenne($allAges),
//                'moyenne_age' => $userService->moyenne($userService->getColumn($students, 'age')),
//                'moyenne_age' => $userService->moyenne(array_column($students, 'age')),
            ]
        );
    }

    /**
     * @Route("/user/{id}", name="user_profile")
     * @param User $user
     * @return Response
     */
//    public function userProfileAction($id)
    public function userProfileAction(User $user = null) // = null pour mettre valeur par defaut
    {
        // Mettre User au lieu de id dans la signature de la methode
        // indique à Symfony que 'id' de la route est celui de la classe User
        // De cette façon, l'objet User $user est déjà initialisé (ou null si aucun user d'id 'id' n'existe)

//        $userId = $r->get('id');

//        $json = @file_get_contents(
//            $this->generateUrl('api_user_by_id', ['id'=>$userId], UrlGeneratorInterface::ABSOLUTE_URL)
//        ); // Permet d'avoir l'url à atteindre à partir de la route correspondante
//
//        $user = json_decode($json, true);


//        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if ($user) {

            return $this->render(
                'user/pages/profile.html.twig',
                [
                    'user' => $user,
                ]);
        } else {
            return $this->render(
                'user/pages/profileNotFound.html.twig',
                []
            );
        }

    }

    /**
     * @Route("/user/sendmail/{email}", name="user_send_email")
     * @param Request $r
     * @param \Swift_Mailer $mailer
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    public function sendEmailAction(Request $r, \Swift_Mailer $mailer, LoggerInterface $logger)
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

        $logger->info($email);

        $isSend = $mailer->send($message);

        return $this->json(
            [
                'status'=>'ok',
            ]
        );

    }

    /**
     * @Route("/users/delete-minors", name="delete-minors")
     */
    public function deleteMinorsAction(EntityManagerInterface $em)
    {
        $userRepo = $em->getRepository(User::class);

        $rep = $userRepo->deleteMinors();

        dump($rep);

        return new Response();
    }

    /**
     * @Route("/group/{groupName}/users")
     */
    public function usersInGroup($groupName, EntityManagerInterface $em)
    {
        $users = $em->getRepository(User::class)->getUsersInGroup($groupName);

        return $this->render(
            'group/pages/users-in-group.html.twig',
            [
                'users'=>$users,
                'groupName'=>$groupName,
            ]
        );
    }

    /**
     * @Route("users/less-than-two-images")
     */
    public function lessThanTwoPictures(EntityManagerInterface $em)
    {
        $users = $em->getRepository(User::class)->lessThanNbPictures(2);

        dump($users);
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

    /**
     * @return array
     */
    private function loadStudentsV3(){
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        return $userRepo->findAll();
    }
}

?>
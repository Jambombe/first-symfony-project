<?php
// src/AppBundle/Controller/Api/UserController.php
namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserController extends Controller
{

    // La route est définie dans AppBundle\Resources\config\routing_api.yml
    public function getAllAction()
    {
        return $this->json($this->usersV2());
    }


    public function getByIdAction(Request $r)
    {
        $id = $r->get('id'); // On recupère le parametre 'id' (defini dans la route)

        $user = $this->getByIdV2($id);

        return $this->json($user);
    }

    private function getById($id)
    {
        $users = $this->users();

        foreach ($users as $user)
        {
            if ($user['id'] == $id) {
                return $user;
                break;
            }
        }
    }

    private function getByIdV2($id){
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        return $userRepo->find($id);
    }

    public function getByEmailAction(Request $r)
    {
//        $email = $r->get('email'); // On recupère le parametre 'email' (defini dans la route)
        $email = $r->attributes->filter('email', null, FILTER_VALIDATE_EMAIL);
        // Si on a bien une adresse email
//        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        if ($email)
        {
            if ( ! empty($rep = $this->getByEmail($email)))
            {
                return $this->json($rep, JsonResponse::HTTP_FOUND);
            } else {
                return $this->jsonNotFound();
            }
        } else {
            return $this->jsonNotFound();
        }
        return false;
    }

    private function getByEmail($email)
    {
        $users = $this->usersV2();

        foreach ($users as $user)
        {
            if ($user['email'] == $email) {
                return $user;
                break;
            }
        }
    }

    private function jsonNotFound()
    {
        return $this->json(array('status' => 'notFound'), JsonResponse::HTTP_NOT_FOUND);
    }

    private function users()
    {
        $array = array();

        array_push($array, array('id'=> 1, 'nom'=>'a', 'prenom'=>'a', 'date_naissance'=>'29-01-2019', 'age'=> 1, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 2, 'nom'=>'b', 'prenom'=>'b', 'date_naissance'=>'01-01-1998', 'age'=> 2, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 3, 'nom'=>'c', 'prenom'=>'c', 'date_naissance'=>'01-01-1998', 'age'=> 3, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 4, 'nom'=>'d', 'prenom'=>'d', 'date_naissance'=>'01-01-1998', 'age'=> 4, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 5, 'nom'=>'e', 'prenom'=>'e', 'date_naissance'=>'01-01-1998', 'age'=> 5, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 6, 'nom'=>'f', 'prenom'=>'f', 'date_naissance'=>'01-01-8751', 'age'=> 6, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 7, 'nom'=>'g', 'prenom'=>'g', 'date_naissance'=>'01-01-1998', 'age'=> 7, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 8, 'nom'=>'h', 'prenom'=>'h', 'date_naissance'=>'01-01-1998', 'age'=> 8, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 9, 'nom'=>'i', 'prenom'=>'i', 'date_naissance'=>'01-01-1998', 'age'=> 90, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 10, 'nom'=>'j', 'prenom'=>'j', 'date_naissance'=>'01-01-1998', 'age'=> 10, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 11, 'nom'=>'k', 'prenom'=>'k', 'date_naissance'=>'01-01-1998', 'age'=> 11, 'email'=>'lucas.barneoudarnaud@gmail.com'));

        return $array;
    }
    private function usersV2(){
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        return $userRepo->findAll();
    }

}
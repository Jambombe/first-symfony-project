<?php
// src/AppBundle/Controller/Api/UserController.php
namespace AppBundle\Controller\Api;

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
        return $this->json($this->users());
    }


    public function getByIdAction(Request $r)
    {
        $id = $r->get('id'); // On recupère le parametre 'id' (defini dans la route)
        return $this->json($this->getById($id));
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

    public function getByEmailAction(Request $r)
    {
        $email = $r->get('email'); // On recupère le parametre 'email' (defini dans la route)

        // Si on a bien une adresse email
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return $this->json($this->getByEmail($email));
        } else {
            return $this->json(null, JsonResponse::HTTP_NOT_FOUND);
        }
        return false;
    }

    private function getByEmail($email)
    {
        $users = $this->users();

        foreach ($users as $user)
        {
            if ($user['email'] == $email) {
                return $user;
                break;
            }
        }
    }

    private function users()
    {
        $array = array();

        array_push($array, array('id'=> 1, 'prenom'=>'a', 'age'=> 1, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 2, 'prenom'=>'b', 'age'=> 2, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 3, 'prenom'=>'c', 'age'=> 3, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 4, 'prenom'=>'d', 'age'=> 4, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 5, 'prenom'=>'e', 'age'=> 5, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 6, 'prenom'=>'f', 'age'=> 6, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 7, 'prenom'=>'g', 'age'=> 7, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 8, 'prenom'=>'h', 'age'=> 8, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 9, 'prenom'=>'i', 'age'=> 9, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 10, 'prenom'=>'j', 'age'=> 10, 'email'=>'lucas.barneoudarnaud@gmail.com'));
        array_push($array, array('id'=> 11, 'prenom'=>'k', 'age'=> 11, 'email'=>'lucas.barneoudarnaud@gmail.com'));

        return $array;
    }

}
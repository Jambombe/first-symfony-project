<?php

namespace AppBundle\Service;

// Le service est son alias sont déclarés ici : app/config/services.yml
class UserService
{

    public function moyenne($array){

        if (count($array) > 0)
            return round(array_sum($array) / count($array));
        else
            return null;
    }

    /**
     * Retourne un array contenant uniquement les données de la colonne '$columnName'
     * @param $array array de array ou de stdClass
     * @param $columnName string : nom de la colonne à récupérer
     * @return array contenant les données en cas de succès, array vide sinon
     */
    public function getColumn($array, $columnName){
        $newArray = array();

        if ($array[0] instanceof \stdClass){
            foreach ($array as $item){
                array_push($newArray, $item->$columnName);
            }
        } elseif (gettype($array[0]) == 'array') {
            foreach ($array as $item){
                array_push($newArray, $item[$columnName]);
            }
        }

        return $newArray;
    }

}
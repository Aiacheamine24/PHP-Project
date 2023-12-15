<?php

namespace App\Controllers;

use App\Models\ModelClothe;

class ClothesFunctions // Make sure to replace YourControllerName with the actual name of your controller
{
    public static function getAllClothes()
    {
        return ModelClothe::getAllClothes();
    }
    public static function getClotheById($clothes_id): array
    {
        $c = ModelClothe::getClotheById($clothes_id);
        return $c;
    }
    public static function insertOne(array $data): int
    {
        return ModelClothe::insertClothe($data);
    }
}

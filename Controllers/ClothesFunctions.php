<?php

namespace App\Controllers;

use App\Models\ModelClothe;

class ClothesFunctions // Make sure to replace YourControllerName with the actual name of your controller
{
    public static function getAllClothes()
    {
        return ModelClothe::getAllClothes(); // Corrected method name
    }
    public static function getClotheById($clothes_id): array
    {
        $c = ModelClothe::getClotheById($clothes_id); // Corrected method name
        return $c;
    }
}

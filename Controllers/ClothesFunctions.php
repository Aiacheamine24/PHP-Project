<?php

namespace App\Controllers;

use App\Models\ModelClothe;

class ClothesFunctions // Make sure to replace YourControllerName with the actual name of your controller
{
    public static function getAllClothes()
    {
        return ModelClothe::getAllClothes(); // Corrected method name
    }
}

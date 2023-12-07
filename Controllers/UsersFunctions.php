<?php

use App\Models\ModelUser;

class UsersFunctions
{
    public static function login($email, $password)
    {
        $user = new ModelUser([
            'email' => $email,
            'password' => $password
        ]);
        return $user->loginUser();
    }
    public static function register($username, $email, $password)
    {
        $user = new ModelUser([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);
        return $user->insertUser();
    }
    public static function getAllUsers()
    {
        $user = new ModelUser();
        return $user->getAllUsers();
    }
}

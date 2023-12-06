<?php
// Model User
namespace App\Models;

class ModelUser extends Model
{
    public $user_id;
    public $username;
    public $email;
    protected $password;
    public $user_type;
    protected $table;
    // Constructeur
    public function __construct(array $data = [])
    {
        // On Initialise les propriétés avec des valeurs par défaut ou null
        $this->user_id = $data['user_id'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->user_type = $data['user_type'] ?? null;
        $this->table = 'users';
        // On appelle le constructeur de la classe parente
        parent::__construct();
    }
    // CRUD
    // Get User by Id
    public function getUser(): array
    {
        $criteria = [];
        // On verifie si user_id est pas undefined sans generer d'erreur
        if (isset($this->user_id)) {
            $criteria['user_id'] = $this->user_id;
        } elseif (isset($this->email)) {
            $criteria['email'] = $this->email;
        } else {
            return []; // Aucun critère spécifié, retourner un tableau vide
        }
        $user = $this->selectByCriteria($criteria)[0] ?? null;
        if ($user) {
            $this->user_id = $user['user_id'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->user_type = $user['user_type'];
        }
        return $user ? $user : [];
    }

    // Insert User
    public function insertUser(): int
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $user = $this->insertHydrate();
        return $user;
    }
    // Update User
    public function updateUser(): int
    {
        // On Verifie si le mot de passe ici est crypté 
        // Si oui on supprime le mot de passe car il na pas ete modifié
        // Si non on crypte le mot de passe
        if (strlen($this->password) == 60) {
            unset($this->password);
        } else {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        // On construit le tableau de condition
        $condition = ['user_id' => $this->user_id ?? null];
        // Si on a pas de user_id On Arrete tout car email peut etre modifié
        if (!$condition['user_id']) {
            return 0;
        }
        // On met a jour l'utilisateur
        $user = $this->updateHydrate($condition);
        return $user;
    }
    // Delete User
    public function deleteUser(): int
    {
        $criteria = [];
        if ($this->user_id) {
            $criteria['user_id'] = $this->user_id;
        } elseif ($this->email) {
            $criteria['email'] = $this->email;
        } else {
            return 0; // Aucun critère spécifié, retourner 0
        }
        $user = $this->deleteHydrate();
        return $user;
    }
    // Login User
    public function loginUser()
    {
        // On Verifie si les informations sont bien renseignées
        if (!$this->email || !$this->password) {
            return false;
        }
        // On supprime toutes les propriétés inutiles
        unset($this->user_id);
        unset($this->username);
        unset($this->user_type);
        // On Cree une copie de password
        $password = $this->password;
        // On recupere l'utilisateur
        $user = $this->getUser();
        // On verifie si l'utilisateur existe
        if (!$user) {
            return false;
        }
        // On verifie si le mot de passe est correct
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        // On supprime le mot de passe
        unset($user['password']);
        // Rendre le user_id int
        $user['user_id'] = intval($user['user_id']);
        // On hydrate l'objet
        $this->hydrate($user);
        // On retourne l'utilisateur
        return $user;
    }
    // Getters Setters
    ///Get the value of user_id 
    public function getUser_id()
    {
        return $this->user_id;
    }
    // Set the value of user_id
    //@return  self 
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    // Get the value of username
    public function getUsername()
    {
        return $this->username;
    }
    // Set the value of username
    //@return  self
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    // Get the value of email
    public function getEmail()
    {
        return $this->email;
    }
    // Set the value of email
    // @return  self
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    // Get the value of password
    public function getPassword()
    {
        return $this->password;
    }
    // Set the value of password
    // @return  self
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    // Get the value of user_type
    public function getUser_type()
    {
        return $this->user_type;
    }
    // Set the value of user_type
    // @return  self
    public function setUser_type($user_type)
    {
        $this->user_type = $user_type;
        return $this;
    }
}

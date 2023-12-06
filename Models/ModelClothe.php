<?php

namespace App\Models;
// Class ModelClothe
class ModelClothe extends Model
{
    protected $table = 'clothes';
    public $clothes_id;
    public $name;
    public $price;
    public $size;
    public $description;
    public $photo_id;
    public $file_path;
    // Constructeur
    public function __construct(array $data = [])
    {
        // On Initialise les propriétés avec des valeurs par défaut ou null
        $this->clothes_id = $data['clothes_id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->price = $data['price'] ?? null;
        $this->size = $data['size'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->photo_id = $data['photo_id'] ?? null;
        $this->file_path = $data['file_path'] ?? null;
        // On appelle le constructeur de la classe parente
        parent::__construct();
    }
    // CRUD
    // Get Clothes
    public function getClotheByClassData(): array
    {
        $criteria = [];
        // On verifie si clothe_id est pas undefined sans generer d'erreur
        if (isset($this->clothes_id)) {
            $criteria['clothes_id'] = $this->clothes_id;
        } elseif (isset($this->name)) {
            $criteria['name'] = $this->name;
        } else {
            return []; // Aucun critère spécifié, retourner un tableau vide
        }
        // On recupere les données de la table clothes
        $clothes = $this->selectByCriteria($criteria)[0] ?? null;
        // On verifie si $clothes existe pas
        // On recupere les données de la table photos directement avec la fonction requete
        $photo = $this->requete("SELECT * FROM photos WHERE clothes_id = {$clothes['clothes_id']}")->fetchAll();
        $clothes = array_merge($clothes, ['photo' => $photo[0] ?? null]);
        // On hydrate l'objet
        $this->hydrate($clothes);
        // On retourne les données
        return $clothes ? $clothes : [];
    }
    // Get All Clothes
    public static function getAllClothes(): array
    {
        // On recupere les données de la table clothes
        $clothes = (new self())->getAll();
        // On recupere les données de la table photos directement avec la fonction requete
        $photos = (new self())->requete("SELECT * FROM photos")->fetchAll();
        // On fusionne les deux tableaux
        $clothes = array_map(function ($clothe) use ($photos) {
            $clothe['photo'] = array_filter($photos, fn ($photo) => $photo['clothes_id'] === $clothe['clothes_id'])[0] ?? null;
            return $clothe;
        }, $clothes);
        // On retourne les données
        return $clothes ? $clothes : [];
    }
    // Insert Clothe
    public static function insertClothe(array $data): int
    {
        // On recupere les données de la table clothes (name, price, size, description)
        $clotheId = (new self([
            'name' => $data['name'],
            'price' => $data['price'],
            'size' => $data['size'],
            'description' => $data['description']
        ]))->insertHydrate();
        // On insere les données de la table photos (clothes_id, file_path)
        (new self([
            'clothes_id' => $clotheId,
            'file_path' => $data['file_path']
        ]))->setTable("photos")->insertHydrate();
        // On retourne l'ID du vêtement
        return $clotheId;
    }
    // Update Clothes
    // Nouvelle méthode pour mettre à jour la table 'photos'
    public function updatePhoto($clotheId, $data): int
    {
        // On met à jour les données de la table photos (file_path)
        return (new self([
            'clothes_id' => $clotheId,
            'file_path' => $data['file_path']
        ]))->setTable("photos")->updateHydrate([
            'clothes_id' => $clotheId
        ]);
    }
    // Méthode pour mettre à jour les données de la table 'clothes' et 'photos'
    public static function updateClothe($clotheId, $data): array
    {
        // Les données non mises à jour sont récupérées avec les anciennes
        $clothe = (new self(["clothes_id" => $clotheId]))->getClotheByClassData();
        // Si $clothe est vide, on arrête tout, sinon, on récupère ses photos
        if (!$clothe) {
            return [];
        }
        // On récupère les données de la table photos directement avec la fonction requête
        $photo = (new self())->requete("SELECT * FROM photos WHERE clothes_id = {$clothe['clothes_id']}")->fetchAll();
        // On fusionne les deux tableaux
        $clothe = array_merge($clothe, ['photo' => $photo[0] ?? null]);
        // On vérifie les données à mettre à jour
        $clothe['name'] = $data['name'] ?? $clothe['name'];
        $clothe['price'] = $data['price'] ?? $clothe['price'];
        $clothe['size'] = $data['size'] ?? $clothe['size'];
        $clothe['description'] = $data['description'] ?? $clothe['description'];
        $clothe['file_path'] = $data['file_path'] ?? $clothe['photo']['file_path'];
        // On met à jour les données de la table 'clothes'
        (new self([
            'name' => $clothe['name'],
            'price' => $clothe['price'],
            'size' => $clothe['size'],
            'description' => $clothe['description']
        ]))->updateHydrate([
            'clothes_id' => $clotheId
        ]);
        // On met à jour les données de la table 'photos'
        (new self([
            'clothes_id' => $clotheId,
            'file_path' => $clothe['file_path']
        ]))->setTable("photos")->updateHydrate([
            'clothes_id' => $clotheId
        ]);
        // On récupère le nouveau vêtement
        return (new self(["clothes_id" => $clotheId]))->getClotheByClassData();
    }
    // Delete Clothes
    public static function deleteClothe($clotheId): int
    {
        // On supprime les données de la table clothes
        return (new self())->delete([
            'clothes_id' => $clotheId
        ]);
    }
    /**
     * Get the value of table
     */
    public function getTable()
    {
        return $this->table;
    }
    /**
     * Set the value of table
     * @return  self
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }
    /**
     * Get the value of clothes_id
     */
    public function getClothes_id()
    {
        return $this->clothes_id;
    }
    /**
     * Set the value of clothes_id
     * @return  self
     */
    public function setClothes_id($clothes_id)
    {
        $this->clothes_id = $clothes_id;
        return $this;
    }
    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set the value of name
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }
    /**
     * Set the value of price
     * @return  self
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    /**
     * Get the value of size
     */
    public function getSize()
    {
        return $this->size;
    }
    /**
     * Set the value of size
     * @return  self
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }
    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Set the value of description
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    /**
     * Get the value of photo_id
     */
    public function getPhoto_id()
    {
        return $this->photo_id;
    }
    /**
     * Set the value of photo_id
     * @return  self
     */
    public function setPhoto_id($photo_id)
    {
        $this->photo_id = $photo_id;
        return $this;
    }
    /**
     * Get the value of file_path
     */
    public function getFile_path()
    {
        return $this->file_path;
    }
    /**
     * Set the value of file_path
     * @return  self
     */
    public function setFile_path($file_path)
    {
        $this->file_path = $file_path;
        return $this;
    }
}

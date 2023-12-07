<?php

namespace App\Models;

use App\Core\ConnectDB;

class Model extends ConnectDB
{
    // Properties
    protected $table = 'users';
    protected $conn;
    // Requête SQL
    public function requete(string $sql, array $attributs = null)
    {
        try {
            // Connexion à la base de données
            $this->conn = $this->getInstance();
            // Si $attributs existe
            if ($attributs !== null) {
                // Préparation de la requête
                $query = $this->conn->prepare($sql);
                // Exécution de la requête
                $query->execute($attributs);
                // Renvoi des résultats
                return $query;
            }
            // Sinon
            // Exécution de la requête
            return $this->conn->query($sql);
        } catch (\Throwable $th) {
            //throw $th;
            echo ('Erreur : ' . $th->getMessage());
        }
    }
    // CRUD
    // Get all
    public function getAll(): array
    {
        // Requête SQL
        $sql = "SELECT * FROM {$this->table}";
        // Renvoi des résultats
        return $this->requete($sql)->fetchAll();
    }
    // Selecy by Criteria
    public function selectByCriteria(array $criteria): array
    {
        // On recupere les clés et les valeurs de $criteria
        $cles = array_keys($criteria);
        $valeurs = array_values($criteria);
        // On construit la requête SQL
        $sql = "SELECT * FROM {$this->table} WHERE ";
        // On Ajoute les AND et les = ?
        $sql .= implode(' AND ', array_map(fn ($cle) => "$cle = ?", $cles)) . ";";
        // Renvoi des résultats
        return $this->requete($sql, $valeurs)->fetchAll();
    }
    // Insert
    public function insert(array $data): int
    {
        // On recupere les clés et les valeurs de $data
        $cles = array_keys($data);
        $valeurs = array_values($data);
        // On construit la requête SQL
        $sql = "INSERT INTO {$this->table} (";
        // On Ajoute les clés
        $sql .= implode(', ', $cles);
        // On Ajoute les valeurs
        $sql .= ") VALUES (";
        // On Ajoute les ?
        $sql .= implode(', ', array_fill(0, count($cles), '?'));
        // On ferme la requête
        $sql .= ");";
        try {
            // Exécution de la requête
            $this->requete($sql, $valeurs);
            // Récupération de l'ID du dernier enregistrement inséré
            $lastInsertId = $this->conn->lastInsertId();
            // Renvoi de l'ID du dernier enregistrement inséré
            return (int)$lastInsertId;
        } catch (\Throwable $th) {
            die('Erreur : ' . $th->getMessage());
        }
    }

    // Insert Hydrate
    public function insertHydrate(): int
    {
        // On recupere les clés et les valeurs de $data
        $object = get_object_vars($this);
        // Detruit les propriétés inutiles
        unset($object['table']);
        unset($object['conn']);
        unset($object['dbhost']);
        unset($object['dbname']);
        unset($object['dbuser']);
        unset($object['dbpass']);
        unset($object['dbcharset']);
        // Supprime les propriétés vides ou null
        $object = array_filter($object);
        // On Appelle la méthode insert
        return $this->insert($object);
    }
    // Update
    public function update(array $data, array $criteria): int
    {
        // On recupere les clés et les valeurs de $data
        $cles = array_keys($data);
        $valeurs = array_values($data);
        // On construit la requête SQL
        $sql = "UPDATE {$this->table} SET ";
        // On Ajoute les clés et les valeurs
        $sql .= implode(' = ?, ', $cles) . ' = ? ';
        // On Ajoute les AND et les = ?
        $sql .= "WHERE " . implode(' AND ', array_map(fn ($cle) => "$cle = ?", array_keys($criteria))) . ";";
        // On fusionne les valeurs
        $valeurs = array_merge($valeurs, array_values($criteria));
        // Renvoi des résultats
        return $this->requete($sql, $valeurs)->rowCount();
    }
    // Update Hydrate
    public function updateHydrate($condition): int
    {
        // On recupere les clés et les valeurs de $data
        $object = get_object_vars($this);
        // Detruit les propriétés inutiles
        unset($object['table']);
        unset($object['conn']);
        unset($object['dbhost']);
        unset($object['dbname']);
        unset($object['dbuser']);
        unset($object['dbpass']);
        unset($object['dbcharset']);
        // Supprime les propriétés vides ou null
        $object = array_filter($object);
        var_dump($object);
        // On appelle la méthode update
        return $this->update($object, $condition);
    }
    // Delete
    public function delete(array $criteria): int
    {
        // On construit la requête SQL
        $sql = "DELETE FROM {$this->table} WHERE ";
        // On Ajoute les AND et les = ?
        $sql .= implode(' AND ', array_map(fn ($cle) => "$cle = ?", array_keys($criteria))) . ";";
        // Renvoi des résultats
        return $this->requete($sql, array_values($criteria))->rowCount();
    }
    // Delete Hydrate
    public function deleteHydrate(): int
    {
        // On recupere les clés et les valeurs de $data
        $object = get_object_vars($this);
        // Detruit les propriétés inutiles
        unset($object['table']);
        unset($object['conn']);
        unset($object['dbhost']);
        unset($object['dbname']);
        unset($object['dbuser']);
        unset($object['dbpass']);
        unset($object['dbcharset']);
        // Supprime les propriétés vides ou null
        $object = array_filter($object);
        // On appelle la méthode delete
        return $this->delete($object);
    }
    // Hydrate
    public function hydrate(array $data): void
    {
        // On recupere les clés et les valeurs de $data
        $cles = array_keys($data);
        $valeurs = array_values($data);
        // On boucle sur les clés
        foreach ($cles as $cle) {
            // On construit le nom du setter correspondant à la clé
            $setter = 'set' . ucfirst($cle);
            // Si le setter existe
            if (method_exists($this, $setter)) {
                // On appelle le setter
                $this->$setter($valeurs[array_search($cle, $cles)]);
            }
        }
    }
}

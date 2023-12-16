<?php

use App\Models\ModelCommande;

class CommandFunctions
{
    public static function insertOne($data, $user_id): int
    {
        $total_price = 0;

        // Préparez les données pour la commande
        $commandData = [
            'user_id' => $user_id,
            'order_date' => date('Y-m-d H:i:s'),
        ];

        // Pour chaque élément dans $data, calculez le prix et ajoutez-le au total
        foreach ($data as $item) {
            $product_id = $item["product_id"];
            $quantity = $item["quantity"];
            $price = $item["price"];

            // Vérifiez si le produit a un prix défini
            if (isset($price)) {
                $total_price += $price * $quantity;
            } else {
                // Gestion des erreurs si le prix n'est pas défini pour le produit
                echo "Prix non défini pour le produit avec product_id $product_id.";
            }
        }

        // Ajoutez le prix total aux données de la commande
        $commandData['total_price'] = $total_price;

        // Créez une instance de ModelCommande avec les données préparées
        $commande = new ModelCommande($commandData);
        // Appel à la méthode d'insertion
        $commandId = $commande->insertHydrate();

        // Si l'insertion a réussi, insérez les articles de commande dans la table "command_items"
        if ($commandId) {
            $commande->insertCommandItems($commandId, $data);
        }
        return $commandId ?? null;
    }

    public static function getAll(): array
    {
        $commande = new ModelCommande([]);
        return $commande->getAll();
    }
}

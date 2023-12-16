<?php

namespace App\Models;

class ModelCommande extends Model
{
    protected $table = "commands";
    public $command_id;
    public $user_id;
    public $total_price;
    public $order_date;

    public function __construct(array $data)
    {
        $this->user_id = $data['user_id'] ?? null;
        $this->total_price = $data['total_price'] ?? null;
        // Date de maintenant
        $this->order_date = date('Y-m-d H:i:s');
        parent::__construct();
    }

    public static function createCommand(array $data, array $items): int
    {
        // Créer une instance de la classe pour la commande principale
        $command = new self([
            'user_id' => $data['user_id'],
            'total_price' => $data['total_price'],
        ]);

        // Insérer la commande principale dans la table "commands"
        $commandId = $command->insertHydrate();

        // Si l'insertion a réussi, insérer les articles de commande dans la table "command_items"
        if ($commandId) {
            $command->insertCommandItems($commandId, $items);
        }

        return $commandId;
    }

    public function insertCommandItems(int $commandId, array $items): void
    {
        foreach ($items as $item) {
            $commandItem = new ModelCommandItem([
                'command_id' => $commandId,
                'clothes_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);

            // Insérer l'article de commande dans la table "command_items"
            $commandItem->insertHydrate();
        }
    }
}

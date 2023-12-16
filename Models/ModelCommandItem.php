<?php

namespace App\Models;

class ModelCommandItem extends Model
{
    protected $table = "command_items";
    public $command_item_id;
    public $command_id;
    public $clothes_id;
    public $quantity;

    public function __construct(array $data)
    {
        $this->command_id = $data['command_id'] ?? null;
        $this->clothes_id = $data['clothes_id'] ?? null;
        $this->quantity = $data['quantity'] ?? null;
        parent::__construct();
    }
}

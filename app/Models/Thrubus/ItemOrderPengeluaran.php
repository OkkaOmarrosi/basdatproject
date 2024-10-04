<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrderPengeluaran extends Model
{

    protected $table = 'item_order_pengeluaran';

    protected $connection = 'mysql2';
    use HasFactory;

}

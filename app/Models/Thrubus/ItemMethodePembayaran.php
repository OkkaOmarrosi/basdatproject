<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMethodePembayaran extends Model
{

    protected $table = 'item_methode_pembayaran';

    protected $connection = 'mysql2';
    use HasFactory;

}

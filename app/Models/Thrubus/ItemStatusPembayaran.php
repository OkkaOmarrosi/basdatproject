<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStatusPembayaran extends Model
{

    protected $table = 'item_status_pembayaran';

    protected $connection = 'mysql2';
    use HasFactory;


}

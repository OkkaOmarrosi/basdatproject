<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemChecklist extends Model
{

    protected $table = 'item_checklist';

    protected $connection = 'mysql2';
    use HasFactory;


}

<?php

namespace App\Models;

use App\Models\Thrubus\ItemChecklist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananChecklist extends Model
{
    use HasFactory;

    protected $table = 'pesanan_checklist';

    protected $fillable = [
        'header_id',
        'iditem_checklist',
        'value',
        'created_at',
        'updated_at',
        'created_by',
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function checklist()
    {
        return $this->belongsTo(ItemChecklist::class, 'iditem_checklist', 'iditem_checklist');
    }
}

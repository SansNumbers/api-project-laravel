<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description'
    ];

    public function post() {
        return $this->belongsTo('\App\Models\Post');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'author',
        'post_id',
        'comment_id',
        'type'
    ];

    public function post() {
        return $this->belongsTo('\App\Models\Post');
    }

    public function comment() {
        return $this->belongsTo('\App\Models\Comment');
    }
}

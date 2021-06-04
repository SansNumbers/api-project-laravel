<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $fillable = [
        'author',
        'title',
        'rating',
        'content',
        'categories',
        'status',
        'locked'
    ];

    protected $casts = [
        'rating' => 'integer',
        'categories' => 'array',
        'status' => 'string'
    ];
}

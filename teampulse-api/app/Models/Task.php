<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'assignee',
        'due',
        'user_id',
    ];

    protected $casts = [
        'due' => 'date:Y-m-d',
    ];
}
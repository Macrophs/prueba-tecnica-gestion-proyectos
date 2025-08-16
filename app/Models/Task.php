<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
        protected $fillable = [
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'project_id',
        'user_id',
    ];
}

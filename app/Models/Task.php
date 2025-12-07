<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'status',
    ];

    // 1. Tapşırığı yazan / dəyişən istifadəçi
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // 2. Tapşırığın şərhləri
    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'content'
    ];

    // Şərhin aid olduğu tapşırıq
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Şərhi yazan istifadəçi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class postLikes extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'username'];

    protected $hidden = ['id', 'updated_at', 'user_id'];

    public function postLikeUsers()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\uuids;

class posts extends Model
{
    use uuids, HasFactory;

    protected $fillable = [
        'author',
        'description',
        'image', 
        'topic',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function postLikeUsers()
    {
        return $this->hasMany(postLikes::class, 'post_id', 'id')->take(5);
    }
}

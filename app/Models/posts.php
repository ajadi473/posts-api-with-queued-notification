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
        'likes'
    ];
}

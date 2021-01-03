<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
    */
    protected $hidden = [];

    /*
     * Get the comments for the blog post.
    */
    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    /*
     * Get the comments for the blog post.
    */
    public function images()
    {
        return $this->hasMany(Images::class);
    }

    /*
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

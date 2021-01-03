<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'text',
        'file_path',
        'user_id',
        'item_id',
        'comments_type_id',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
     * Get the comments for the blog post.
    */
    public function item()
    {
        return $this->belongTo(Items::class);
    }

    /*
     * Get the post that owns the comment.
     */
    public function comments_type()
    {
        return $this->belongsTo(Comments_types::class);
    }
}

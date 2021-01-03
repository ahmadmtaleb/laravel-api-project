<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments_types extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'id',
        'type_name'
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
    public function items()
    {
        return $this->hasMany(Comments::class);
    }
}

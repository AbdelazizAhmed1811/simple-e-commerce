<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'img',
        'categories',
        'price'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];



    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'product_id');
    }

    // public function memberShip(): BelongsToMany
    // {
    //     return $this->belongsToMany(Project::class, Member::class);
    // }

    // public function projects(): HasMany
    // {
    //     return $this->hasMany(Project::class, 'creator_id');
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'price', 'status', 'category', 'category_id', 'image_path'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function rentals() {
        return $this->hasMany(Rental::class);
    }

    public function categoryRecord()
    {
        return $this->belongsTo(Category::class);
    }
}

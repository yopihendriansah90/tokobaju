<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('category_icon')
            ->singleFile();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

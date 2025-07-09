<?php

namespace App\Models;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'author',
        'description',
        'price',
        'stock',
        'published_year',
        'cover_image_url',
        'private_file_path',
    ];
    /**
     * Generate slug from title
     *
     * @param string $title
     * @return string
     */
    public static function generateUniqueSlug($title, $id = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Cek apakah slug sudah ada (kecuali untuk book yang sedang di-update)
        while (static::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Boot method to auto-generate unique slug when creating
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            if (empty($book->slug) && !empty($book->title)) {
                $book->slug = static::generateUniqueSlug($book->title);
            }
        });

        static::updating(function ($book) {
            if ($book->isDirty('title') && !$book->isDirty('slug')) {
                $book->slug = static::generateUniqueSlug($book->title, $book->id);
            }
        });
    }



    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MyFavoriteSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'image',
        'description',
        'genre',
        'release_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cache helper method
    public static function getCachedSubjects($limit = null)
    {
        $cacheKey = 'favorite_subjects' . ($limit ? '_' . $limit : '');
        
        return Cache::remember($cacheKey, 3600, function () use ($limit) {
            $query = static::with('user')->latest();
            
            return $limit ? $query->take($limit)->get() : $query->get();
        });
    }
}

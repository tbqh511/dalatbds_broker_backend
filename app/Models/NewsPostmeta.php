<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPostmeta extends Model
{
    use HasFactory;

    protected $table = 'news_postmeta';
    protected $primaryKey = 'meta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'news_post_id',
        'meta_key',
        'meta_value',
    ];

    /**
     * Get the post that owns the meta.
     */
    public function post()
    {
        return $this->belongsTo(NewsPost::class, 'news_post_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTermTaxonomy extends Model
{
    use HasFactory;

    protected $table = 'news_term_taxonomy';
    protected $primaryKey = 'term_taxonomy_id';

    protected $fillable = [
        'term_id',
        'taxonomy',
        'description',
        'parent',
        'count',
    ];

    public function term()
    {
        return $this->belongsTo(NewsTerm::class, 'term_id', 'term_id');
    }

    public function posts()
    {
        return $this->belongsToMany(NewsPost::class, 'news_term_relationships', 'term_taxonomy_id', 'object_id');
    }
}

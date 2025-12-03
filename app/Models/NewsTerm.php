<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTerm extends Model
{
    use HasFactory;

    protected $table = 'news_terms';
    protected $primaryKey = 'term_id';

    protected $fillable = [
        'name',
        'slug',
        'term_group',
    ];

    public function taxonomy()
    {
        return $this->hasOne(NewsTermTaxonomy::class, 'term_id', 'term_id');
    }
}

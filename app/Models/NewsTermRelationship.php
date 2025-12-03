<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class NewsTermRelationship extends Pivot
{
    protected $table = 'news_term_relationships';
    public $timestamps = false;
    protected $primaryKey = ['object_id', 'term_taxonomy_id'];
    public $incrementing = false;
}

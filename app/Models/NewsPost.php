<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPost extends Model
{
    use HasFactory;

    protected $table = 'news_posts';
    protected $primaryKey = 'ID';

    protected $dates = [
        'post_date',
        'post_date_gmt',
        'post_modified',
        'post_modified_gmt',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count',
    ];

    /**
     * Get the meta for the post.
     */
    public function meta()
    {
        return $this->hasMany(NewsPostmeta::class, 'news_post_id');
    }

    /**
     * Get all taxonomies for the post.
     */
    public function taxonomies()
    {
        return $this->belongsToMany(
            NewsTermTaxonomy::class,
            'news_term_relationships',
            'object_id',
            'term_taxonomy_id'
        );
    }

    /**
     * Get categories for the post.
     */
    public function categories()
    {
        return $this->taxonomies()->where('taxonomy', 'category')->with('term');
    }

    /**
     * Get tags for the post.
     */
    public function tags()
    {
        return $this->taxonomies()->where('taxonomy', 'post_tag')->with('term');
    }
}
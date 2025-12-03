<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Models\NewsTerm;
use App\Models\NewsTermTaxonomy;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = NewsPost::with('tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->orderBy('post_date', 'desc')
            ->paginate(10);

        // Fetch Categories with counts
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->get();

        // Fetch Tags
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->get();

        return view('frontend.news.index', compact('news', 'categories', 'tags'));
    }

    /**
     * Filter posts by category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function category($slug)
    {
        $term = NewsTerm::where('slug', $slug)->firstOrFail();

        $news = NewsPost::whereHas('taxonomies', function ($query) use ($term) {
                $query->where('taxonomy', 'category')
                      ->where('news_term_taxonomy.term_id', $term->term_id);
            })
            ->with('tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->orderBy('post_date', 'desc')
            ->paginate(10);

        // Fetch Categories with counts
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->get();

        // Fetch Tags
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->get();

        return view('frontend.news.index', compact('news', 'categories', 'tags'));
    }

    /**
     * Filter posts by tag.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function tag($slug)
    {
        $term = NewsTerm::where('slug', $slug)->firstOrFail();

        $news = NewsPost::whereHas('taxonomies', function ($query) use ($term) {
                $query->where('taxonomy', 'post_tag')
                      ->where('news_term_taxonomy.term_id', $term->term_id);
            })
            ->with('tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->orderBy('post_date', 'desc')
            ->paginate(10);

        // Fetch Categories with counts
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->get();

        // Fetch Tags
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->get();

        return view('frontend.news.index', compact('news', 'categories', 'tags'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = NewsPost::where('post_name', $slug)
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->firstOrFail();

        return view('frontend.news.show', compact('post'));
    }

    /**
     * Get recent news for the home page.
     *
     * @return \Illuminate\Http\Response
     */
    public static function getRecentNews($limit = 5)
    {
        return NewsPost::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->orderBy('post_date', 'desc')
            ->limit($limit)
            ->get();
    }
}

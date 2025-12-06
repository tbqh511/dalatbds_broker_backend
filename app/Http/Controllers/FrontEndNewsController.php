<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Models\NewsTerm;
use App\Models\NewsTermTaxonomy;
use Illuminate\Http\Request;

class FrontEndNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $q = request('se');
        $newsQuery = NewsPost::with('tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish');

        if (!empty($q)) {
            $newsQuery->where(function ($qq) use ($q) {
                $qq->where('post_title', 'like', "%{$q}%")
                   ->orWhere('post_content', 'like', "%{$q}%")
                   ->orWhere('post_excerpt', 'like', "%{$q}%");
            });
        }

        $news = $newsQuery->orderBy('post_date', 'desc')->paginate(10);
        $news->appends(request()->query());

        // Fetch Categories with counts
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->withCount(['posts as count'])
            ->get();

        // Fetch Tags
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->get();

        // Fetch months (year + month) that have posts, with counts
        $months = NewsPost::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->selectRaw('YEAR(post_date) as year, MONTH(post_date) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('frontends.news.index', compact('news', 'categories', 'tags', 'months'));

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

        $q = request('se');
        $newsQuery = NewsPost::whereHas('taxonomies', function ($query) use ($term) {

                $query->where('taxonomy', 'category')
                      ->where('news_term_taxonomy.term_id', $term->term_id);
            })
            ->with('tags')
            ->where('post_type', 'post')

            ->where('post_status', 'publish');

        if (!empty($q)) {
            $newsQuery->where(function ($qq) use ($q) {
                $qq->where('post_title', 'like', "%{$q}%")
                   ->orWhere('post_content', 'like', "%{$q}%")
                   ->orWhere('post_excerpt', 'like', "%{$q}%");
            });
        }

        $news = $newsQuery->orderBy('post_date', 'desc')->paginate(10);
        $news->appends(request()->query());

        // Fetch Categories with counts
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->withCount(['posts as count'])
            ->get();

        // Fetch Tags
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->get();

        // Fetch months
        $months = NewsPost::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->selectRaw('YEAR(post_date) as year, MONTH(post_date) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('frontends.news.index', compact('news', 'categories', 'tags', 'months'));

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

        $q = request('se');
        $newsQuery = NewsPost::whereHas('taxonomies', function ($query) use ($term) {

                $query->where('taxonomy', 'post_tag')
                      ->where('news_term_taxonomy.term_id', $term->term_id);
            })
            ->with('tags')
            ->where('post_type', 'post')

            ->where('post_status', 'publish');

        if (!empty($q)) {
            $newsQuery->where(function ($qq) use ($q) {
                $qq->where('post_title', 'like', "%{$q}%")
                   ->orWhere('post_content', 'like', "%{$q}%")
                   ->orWhere('post_excerpt', 'like', "%{$q}%");
            });
        }

        $news = $newsQuery->orderBy('post_date', 'desc')->paginate(10);
        $news->appends(request()->query());


        // Fetch Categories with counts
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')

            ->withCount(['posts as count'])

            ->get();

        // Fetch Tags
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->get();

        // Fetch months
        $months = NewsPost::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->selectRaw('YEAR(post_date) as year, MONTH(post_date) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('frontends.news.index', compact('news', 'categories', 'tags', 'months'));
    }

    /**
     * Filter posts by year and month.
     *
     * @param  int  $year
     * @param  int  $month
     * @return \Illuminate\Http\Response
     */
    public function month($year, $month)
    {
        $q = request('se');
        $newsQuery = NewsPost::with('tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->whereYear('post_date', $year)
            ->whereMonth('post_date', $month);

        if (!empty($q)) {
            $newsQuery->where(function ($qq) use ($q) {
                $qq->where('post_title', 'like', "%{$q}%")
                   ->orWhere('post_content', 'like', "%{$q}%")
                   ->orWhere('post_excerpt', 'like', "%{$q}%");
            });
        }

        $news = $newsQuery->orderBy('post_date', 'desc')->paginate(10);
        $news->appends(request()->query());

        // Fetch Categories with counts
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->withCount(['posts as count'])
            ->get();

        // Fetch Tags
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->get();

        return view('frontends.news.index', compact('news', 'categories', 'tags'));

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


        // Increment view counter (stored in comment_count) once per session
        try {
            $viewed = session()->get('viewed_news_posts', []);
            $postKey = $post->getKey();
            if (!in_array($postKey, $viewed, true)) {
                $post->increment('comment_count');
                $post->refresh();
                $viewed[] = $postKey;
                session()->put('viewed_news_posts', $viewed);
            }
        } catch (\Throwable $e) {
            // If increment fails for any reason, ignore to avoid breaking the page
        }

        // Fetch Categories with counts
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->withCount(['posts as count'])
            ->get();

        // Fetch Tags
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->get();

        // Fetch months
        $months = NewsPost::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->selectRaw('YEAR(post_date) as year, MONTH(post_date) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('frontends.news.show', compact('post','categories', 'tags', 'months'));

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

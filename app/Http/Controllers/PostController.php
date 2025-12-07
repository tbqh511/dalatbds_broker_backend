<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Models\NewsPostmeta;
use App\Models\NewsTerm;
use App\Models\NewsTermTaxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = NewsPost::where('post_type', 'post')->orderBy('post_date', 'desc')->paginate(10);

        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->get()
            ->map(function ($tax) {
                return $tax->term;
            });

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get all categories (terms with 'category' taxonomy)
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->get()
            ->map(function ($tax) {
                return $tax->term;
            });

        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_excerpt' => 'nullable|string',
            'post_status' => 'required|in:publish,draft',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $post = new NewsPost();
        $post->post_author = auth()->id() ?? 0;
        $post->post_type = 'post'; // Giữ lại từ HEAD

        $post->post_date = now();
        $post->post_date_gmt = now();
        $post->post_content = $request->post_content;
        $post->post_title = $request->post_title;
        $post->post_excerpt = $request->post_excerpt;
        $post->post_status = $request->post_status;

        $slug = Str::slug($request->post_title);
        $base = $slug;
        $i = 1;
        while (NewsPost::where('post_name', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $post->post_name = $slug;

        $post->post_modified = now();
        $post->post_modified_gmt = now();
        $post->save();

        // Thumbnail handling (Giữ lại từ HEAD)
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('images/posts', 'public');
            NewsPostmeta::create([
                'news_post_id' => $post->ID,
                'meta_key' => '_thumbnail',
                'meta_value' => $path,
            ]);
        }

        // Handle Categories
        if ($request->has('categories')) {
            // Find taxonomy IDs for selected category term IDs
            $taxonomyIds = NewsTermTaxonomy::where('taxonomy', 'category')
                ->whereIn('term_id', $request->categories)
                ->pluck('term_taxonomy_id');
            $post->taxonomies()->attach($taxonomyIds);

            // Update counts
            foreach ($taxonomyIds as $tid) {
                NewsTermTaxonomy::where('term_taxonomy_id', $tid)->increment('count');
            }
        }

        // Handle Tags
        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            $tagTaxonomyIds = [];
            foreach ($tags as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName)) continue;

                $slug = Str::slug($tagName);

                // Find or create term
                $term = NewsTerm::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $tagName]
                );

                // Find or create taxonomy
                $taxonomy = NewsTermTaxonomy::firstOrCreate(
                    ['term_id' => $term->term_id, 'taxonomy' => 'post_tag']
                );

                $tagTaxonomyIds[] = $taxonomy->term_taxonomy_id;
            }

            if (!empty($tagTaxonomyIds)) {
                $post->taxonomies()->attach($tagTaxonomyIds);
                foreach ($tagTaxonomyIds as $tid) {
                    NewsTermTaxonomy::where('term_taxonomy_id', $tid)->increment('count');
                }
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = NewsPost::findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = NewsPost::findOrFail($id);
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->get()
            ->map(function ($tax) {
                return $tax->term;
            });

        // Get selected categories
        $selectedCategories = $post->categories->pluck('term_id')->toArray();

        // Get tags as comma separated string
        $tags = $post->tags->map(function ($tax) {
            return $tax->term->name;
        })->implode(', ');

        return view('admin.posts.edit', compact('post', 'categories', 'selectedCategories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_excerpt' => 'nullable|string',
            'post_status' => 'required|in:publish,draft',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $post = NewsPost::findOrFail($id);
        $post->post_content = $request->post_content;
        $post->post_title = $request->post_title;
        $post->post_excerpt = $request->post_excerpt;
        $post->post_status = $request->post_status;

        // keep or regenerate a unique slug if title changed
        $newSlug = Str::slug($request->post_title);
        if ($newSlug !== $post->post_name) {
            $base = $newSlug;
            $i = 1;
            while (NewsPost::where('post_name', $newSlug)->where('ID', '!=', $post->ID)->exists()) {
                $newSlug = $base . '-' . $i++;
            }
            $post->post_name = $newSlug;
        }

        $post->post_modified = now();
        $post->post_modified_gmt = now();
        $post->save();

        // Thumbnail handling: replace existing meta (Giữ lại từ HEAD)
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('images/posts', 'public');

            NewsPostmeta::where('news_post_id', $post->ID)->where('meta_key', '_thumbnail')->delete();
            NewsPostmeta::create([
                'news_post_id' => $post->ID,
                'meta_key' => '_thumbnail',
                'meta_value' => $path,
            ]);
        }

        // Handle Categories
        // Sync categories: detach all categories then attach new ones
        // First get current category taxonomy IDs to decrement count
        $currentCatTaxIds = $post->categories()->pluck('news_term_taxonomy.term_taxonomy_id');
        $post->taxonomies()->detach($currentCatTaxIds);
        NewsTermTaxonomy::whereIn('term_taxonomy_id', $currentCatTaxIds)->decrement('count');

        if ($request->has('categories')) {
            $taxonomyIds = NewsTermTaxonomy::where('taxonomy', 'category')
                ->whereIn('term_id', $request->categories)
                ->pluck('term_taxonomy_id');
            $post->taxonomies()->attach($taxonomyIds);
            NewsTermTaxonomy::whereIn('term_taxonomy_id', $taxonomyIds)->increment('count');
        }

        // Handle Tags
        // Sync tags
        $currentTagTaxIds = $post->tags()->pluck('news_term_taxonomy.term_taxonomy_id');
        $post->taxonomies()->detach($currentTagTaxIds);
        NewsTermTaxonomy::whereIn('term_taxonomy_id', $currentTagTaxIds)->decrement('count');

        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            $tagTaxonomyIds = [];
            foreach ($tags as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName)) continue;

                $slug = Str::slug($tagName);

                $term = NewsTerm::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $tagName]
                );

                $taxonomy = NewsTermTaxonomy::firstOrCreate(
                    ['term_id' => $term->term_id, 'taxonomy' => 'post_tag']
                );

                $tagTaxonomyIds[] = $taxonomy->term_taxonomy_id;
            }

            if (!empty($tagTaxonomyIds)) {
                $post->taxonomies()->attach($tagTaxonomyIds);
                NewsTermTaxonomy::whereIn('term_taxonomy_id', $tagTaxonomyIds)->increment('count');
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = NewsPost::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được xóa thành công.');
    }

    /**
     * Get posts list for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostsList()
    {
        $offset = request('start', 0);
        $limit = request('length', 10);

        // Standard DataTables search
        $search = null;
        $searchInput = request()->input('search');
        if (is_array($searchInput)) {
            $search = $searchInput['value'] ?? null;
        }

        // Custom search param from the client (overrides standard if standard is empty,
        // or just acts as the primary search source since standard input might be hidden)
        $searchText = request('search_text');
        if (empty($search) && !empty($searchText)) {
            $search = $searchText;
        }

        $query = NewsPost::where('post_type', 'post');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('post_title', 'LIKE', "%{$search}%")
                  ->orWhere('post_content', 'LIKE', "%{$search}%");
            });
        }

        $category = request('category');
        if ($category) {
            $query->whereHas('taxonomies', function ($q) use ($category) {
                $q->where('taxonomy', 'category')->where('term_id', $category);
            });
        }

        $status = request('status');
        if ($status) {
            $query->where('post_status', $status);
        }

        $totalRecords = NewsPost::where('post_type', 'post')->count(); // Total records without filter
        $filteredRecords = $query->count(); // Total records with filter

        $posts = $query->orderBy('post_date', 'desc')
                      ->skip($offset)
                      ->take($limit)
                      ->get();

        $data = [];
        foreach ($posts as $post) {
            $deleteForm = '<form action="' . route('admin.posts.destroy', $post->ID) . '" method="POST" style="display:inline;">';
            $deleteForm .= csrf_field();
            $deleteForm .= method_field('DELETE');
            $deleteForm .= '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Bạn có chắc chắn muốn xóa?\')">Xóa</button>';
            $deleteForm .= '</form>';

            // thumbnail
            $thumbMeta = $post->meta()->where('meta_key', '_thumbnail')->first();
            $thumbHtml = '';
            if ($thumbMeta && $thumbMeta->meta_value) {
                $thumbHtml = '<img src="' . asset('storage/' . $thumbMeta->meta_value) . '" style="max-width:80px; height:auto;"/>';
            }

            $data[] = [
                'ID' => $post->ID,
                'post_title' => $post->post_title,
                'post_status' => $post->post_status,
                'thumbnail' => $thumbHtml,
                'post_date' => optional($post->post_date)->format('Y-m-d H:i:s'),
                'actions' => '<a href="' . route('admin.posts.edit', $post->ID) . '" class="btn btn-sm btn-primary">Sửa</a> ' . $deleteForm
            ];
        }

        return response()->json([
            'draw' => intval(request('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }
}
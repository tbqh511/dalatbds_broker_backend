<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Models\NewsPostmeta;
use App\Models\NewsTerm;
use App\Models\NewsTermTaxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsPostApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $tagId = $request->input('tag_id');

        $query = NewsPost::with(['categories', 'tags', 'meta'])
            ->where('post_type', 'post')
            ->where('post_status', 'publish');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('post_title', 'LIKE', "%{$search}%")
                  ->orWhere('post_content', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('news_term_taxonomy.term_id', $categoryId);
            });
        }

        if (!empty($tagId)) {
            $query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('news_term_taxonomy.term_id', $tagId);
            });
        }

        $total = $query->count();
        $posts = $query->orderBy('post_date', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get();

        if ($posts->isNotEmpty()) {
            return response()->json([
                'error' => false,
                'message' => 'Data Fetch Successfully',
                'total' => $total,
                'data' => $posts
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'No data found!',
            'data' => []
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = NewsPost::with(['categories', 'tags', 'meta'])
            ->where('post_type', 'post')
            ->find($id);

        if ($post) {
            return response()->json([
                'error' => false,
                'message' => 'Data Fetch Successfully',
                'data' => $post
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'News Post not found!',
            'data' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_excerpt' => 'nullable|string',
            'post_status' => 'nullable|in:publish,draft',
            'categories' => 'nullable|array',
            'tags' => 'nullable|array',
            'meta' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            DB::beginTransaction();
            
            // Get Current User Role
            $user = auth()->user();
            $role = $user->role ?? 'customer';
            
            // Determine Post Status based on Role
            $status = $request->post_status ?? 'publish';
            if (in_array($role, ['sales', 'customer'])) {
                $status = 'draft';
            }

            $post = new NewsPost();
            $post->post_author = auth()->id() ?? 0;
            $post->post_date = now();
            $post->post_date_gmt = now();
            $post->post_content = $request->post_content;
            $post->post_title = $request->post_title;
            $post->post_excerpt = $request->post_excerpt;
            $post->post_status = $status;
            $post->post_name = Str::slug($request->post_title);
            $post->post_modified = now();
            $post->post_modified_gmt = now();
            $post->save();

            // Handle Categories
            if ($request->has('categories') && !empty($request->categories)) {
                $taxonomyIds = NewsTermTaxonomy::where('taxonomy', 'category')
                    ->whereIn('term_id', $request->categories)
                    ->pluck('term_taxonomy_id');

                $post->taxonomies()->attach($taxonomyIds);

                NewsTermTaxonomy::whereIn('term_taxonomy_id', $taxonomyIds)->increment('count');
            }

            // Handle Tags
            if ($request->has('tags') && !empty($request->tags)) {
                $tagTaxonomyIds = [];
                foreach ($request->tags as $tagName) {
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

            // Handle Meta
            if ($request->has('meta') && !empty($request->meta)) {
                foreach ($request->meta as $key => $value) {
                    NewsPostmeta::create([
                        'news_post_id' => $post->ID,
                        'meta_key' => $key,
                        'meta_value' => $value
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'News Post created successfully',
                'data' => $post->load(['categories', 'tags', 'meta'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to create News Post: ' . $e->getMessage()
            ]);
        }
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
        $post = NewsPost::find($id);

        if (!$post) {
            return response()->json([
                'error' => true,
                'message' => 'News Post not found!'
            ]);
        }

        // Check ownership or permission
        $user = auth()->user();
        $role = $user->role ?? 'customer';
        
        // Sales/Customer can only update their own posts
        if (in_array($role, ['sales', 'customer']) && $post->post_author != $user->id) {
             return response()->json([
                'error' => true,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'post_title' => 'nullable|string|max:255',
            'post_content' => 'nullable|string',
            'post_excerpt' => 'nullable|string',
            'post_status' => 'nullable|in:publish,draft',
            'categories' => 'nullable|array',
            'tags' => 'nullable|array',
            'meta' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            DB::beginTransaction();

            if ($request->has('post_title')) {
                $post->post_title = $request->post_title;
                $post->post_name = Str::slug($request->post_title);
            }
            if ($request->has('post_content')) $post->post_content = $request->post_content;
            if ($request->has('post_excerpt')) $post->post_excerpt = $request->post_excerpt;
            
            // Handle Post Status update permission
            if ($request->has('post_status')) {
                if (in_array($role, ['sales', 'customer']) && $request->post_status == 'publish') {
                    // Prevent sales/customer from publishing directly
                    // Keep existing status or force draft if logic requires
                    // For now, ignore publish request or set to draft
                    $post->post_status = 'draft'; 
                } else {
                    $post->post_status = $request->post_status;
                }
            }

            $post->post_modified = now();
            $post->post_modified_gmt = now();
            $post->save();

            // Sync Categories
            if ($request->has('categories')) {
                // Detach current categories
                $currentCatTaxIds = $post->categories()->pluck('news_term_taxonomy.term_taxonomy_id');
                $post->taxonomies()->detach($currentCatTaxIds);
                NewsTermTaxonomy::whereIn('term_taxonomy_id', $currentCatTaxIds)->decrement('count');

                // Attach new categories
                if (!empty($request->categories)) {
                    $taxonomyIds = NewsTermTaxonomy::where('taxonomy', 'category')
                        ->whereIn('term_id', $request->categories)
                        ->pluck('term_taxonomy_id');

                    $post->taxonomies()->attach($taxonomyIds);
                    NewsTermTaxonomy::whereIn('term_taxonomy_id', $taxonomyIds)->increment('count');
                }
            }

            // Sync Tags
            if ($request->has('tags')) {
                // Detach current tags
                $currentTagTaxIds = $post->tags()->pluck('news_term_taxonomy.term_taxonomy_id');
                $post->taxonomies()->detach($currentTagTaxIds);
                NewsTermTaxonomy::whereIn('term_taxonomy_id', $currentTagTaxIds)->decrement('count');

                // Attach new tags
                if (!empty($request->tags)) {
                    $tagTaxonomyIds = [];
                    foreach ($request->tags as $tagName) {
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
            }

            // Handle Meta
            if ($request->has('meta') && is_array($request->meta)) {
                foreach ($request->meta as $key => $value) {
                    NewsPostmeta::updateOrCreate(
                        ['news_post_id' => $post->ID, 'meta_key' => $key],
                        ['meta_value' => $value]
                    );
                }
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'News Post updated successfully',
                'data' => $post->load(['categories', 'tags', 'meta'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to update News Post: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = NewsPost::find($id);

        if (!$post) {
            return response()->json([
                'error' => true,
                'message' => 'News Post not found!'
            ]);
        }

        try {
            DB::beginTransaction();

            // Decrement counts for associated taxonomies
            $taxIds = $post->taxonomies()->pluck('news_term_taxonomy.term_taxonomy_id');
            if ($taxIds->isNotEmpty()) {
                NewsTermTaxonomy::whereIn('term_taxonomy_id', $taxIds)->decrement('count');
            }

            // Detach taxonomies
            $post->taxonomies()->detach();

            // Delete meta (cascade usually handles this in DB, but good to be explicit or if using soft deletes)
            $post->meta()->delete();

            // Delete post
            $post->delete();

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'News Post deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete News Post: ' . $e->getMessage()
            ]);
        }
    }
}

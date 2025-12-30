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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        Log::info('API Store Post - Request Data:', $request->all());
        Log::info('API Store Post - Has Thumbnail?', ['has_file' => $request->hasFile('thumbnail')]);
        if ($request->hasFile('thumbnail')) {
            Log::info('API Store Post - Thumbnail Details:', [
                'mime' => $request->file('thumbnail')->getMimeType(),
                'original_name' => $request->file('thumbnail')->getClientOriginalName(),
                'size' => $request->file('thumbnail')->getSize()
            ]);
        }

        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_excerpt' => 'nullable|string',
            'post_status' => 'nullable|in:publish,draft',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'category_id' => 'nullable|integer|exists:news_term_taxonomy,term_taxonomy_id',

            'categories' => 'nullable|array',
            'tags' => 'nullable|array', // Allow string or array of tags
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
            // if (in_array($role, ['sales', 'customer'])) {
            //     $status = 'draft';
            // }

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

            // Thumbnail handling
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('images/posts', 'public');
                NewsPostmeta::create([
                    'news_post_id' => $post->ID,
                    'meta_key' => '_thumbnail',
                    'meta_value' => $path,
                ]);

                try {
                    $filename = basename($path);
                    $publicDir = public_path('assets/images/posts');
                    if (!File::isDirectory($publicDir)) {
                        File::makeDirectory($publicDir, 0755, true);
                    }
                    $source = storage_path('app/public/' . $path);
                    $dest = $publicDir . '/' . $filename;
                    if (File::exists($source) && !File::exists($dest)) {
                        File::copy($source, $dest);
                    }
                } catch (\Exception $e) {}
            }

            // Handle Categories
            $this->processCategories($post, $request);

            // Handle Tags (Unified 'tags' and 'tags_input')
            $this->processTags($post, $request);

            // Handle Meta
             if ($request->has('meta') && !empty($request->meta)) {
                foreach ($request->meta as $key => $value) {
                     NewsPostmeta::create([
                        'news_post_id' => $post->ID,
                        'meta_key' => $key,
                        'meta_value' => $value,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'News Post created successfully',
                'data' => $post
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
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
        $post = NewsPost::where('post_type', 'post')->find($id);

        if (!$post) {
            return response()->json([
                'error' => true,
                'message' => 'News Post not found!'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_excerpt' => 'nullable|string',
            'post_status' => 'nullable|in:publish,draft',
            'category_id' => 'nullable|integer|exists:news_term_taxonomy,term_taxonomy_id',
            'categories' => 'nullable|array',
            'tags' => 'nullable' // Allow string or array
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            DB::beginTransaction();

            $post->post_content = $request->post_content;
            $post->post_title = $request->post_title;
            $post->post_excerpt = $request->post_excerpt;
            if ($request->has('post_status')) {
                 $post->post_status = $request->post_status;
            }
            $post->post_name = Str::slug($request->post_title);
            $post->post_modified = now();
            $post->post_modified_gmt = now();
            $post->save();

            // Handle Category
            if ($request->has('category_id')) {
                 // Detach old categories
                $post->taxonomies()->wherePivot('term_taxonomy_id', function ($q) {
                    $q->select('term_taxonomy_id')->from('news_term_taxonomy')->where('taxonomy', 'category');
                })->detach(); // Ideally verify which relationship table is used. Assuming standard pivot.

                if (!empty($request->category_id)) {
                    $post->taxonomies()->attach($request->category_id);
                    NewsTermTaxonomy::where('term_taxonomy_id', $request->category_id)->increment('count');
                }
            } elseif ($request->has('categories')) {
                $post->taxonomies()->wherePivot('term_taxonomy_id', function ($q) {
                    $q->select('term_taxonomy_id')->from('news_term_taxonomy')->where('taxonomy', 'category');
                })->detach();

                if (!empty($request->categories)) {
                    $taxonomyIds = NewsTermTaxonomy::where('taxonomy', 'category')
                        ->whereIn('term_id', $request->categories)
                        ->pluck('term_taxonomy_id');

                    $post->taxonomies()->attach($taxonomyIds);

                    // Update counts (simplified, ideally should decrement old ones too)
                     NewsTermTaxonomy::whereIn('term_taxonomy_id', $taxonomyIds)->increment('count');
                }
            }

            // Handle Tags
            if ($request->has('tags')) {
                 // Detach old tags
                 // Need to know which relation to detach. Assuming 'tags' relation or manual pivot query.
                 // In store method, we used $post->taxonomies()->attach().
                 // To detach only tags, we should filter by taxonomy 'post_tag'.
                 $post->taxonomies()->wherePivot('term_taxonomy_id', function ($q) {
                    $q->select('term_taxonomy_id')->from('news_term_taxonomy')->where('taxonomy', 'post_tag');
                 })->detach();

                $tagNames = $request->tags;
                if (is_string($tagNames)) {
                    $tagNames = explode(',', $tagNames);
                }

                if (is_array($tagNames) && !empty($tagNames)) {
                    $tagTaxonomyIds = [];
                    foreach ($tagNames as $tagName) {
                        $tagName = trim($tagName);
                        if (empty($tagName)) continue;

                        $slug = Str::slug($tagName);

                        $term = NewsTerm::firstOrCreate(
                            ['slug' => $slug],
                            ['name' => $tagName]
                        );

                        $taxonomy = NewsTermTaxonomy::firstOrCreate(
                            ['term_id' => $term->term_id, 'taxonomy' => 'post_tag'],
                            ['description' => '', 'parent' => 0, 'count' => 0]
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
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'News Post updated successfully',
                'data' => $post
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
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
        $post = NewsPost::where('post_type', 'post')->find($id);

        if (!$post) {
            return response()->json([
                'error' => true,
                'message' => 'News Post not found!'
            ]);
        }

        try {
            DB::beginTransaction();

            // Detach taxonomies
            $post->taxonomies()->detach();
            
            // Delete meta
            NewsPostmeta::where('news_post_id', $id)->delete();

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
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Process Categories for Post
     * 
     * @param NewsPost $post
     * @param Request $request
     */
    private function processCategories(NewsPost $post, Request $request)
    {
        $catInput = [];
        
        // Merge inputs
        if ($request->has('categories') && !empty($request->categories)) {
            $input = $request->categories;
            if (is_string($input)) {
                $input = explode(',', $input);
            }
            if (is_array($input)) {
                 $catInput = array_merge($catInput, $input);
            }
        }
        if ($request->has('category_ids') && !empty($request->category_ids)) {
             $ids = is_array($request->category_ids) ? $request->category_ids : explode(',', $request->category_ids);
             $catInput = array_merge($catInput, $ids);
        }
        
        if (empty($catInput)) return;

        // Normalize: trim and unique
        $catInput = array_unique(array_filter(array_map('trim', $catInput), function($value) { return !empty($value); }));
        
        if (empty($catInput)) return;

        Log::info('API Store Post - Consolidated Category IDs', ['ids' => $catInput]);

        // 1. Try to resolve term_ids to term_taxonomy_ids
        $taxonomyIds = NewsTermTaxonomy::where('taxonomy', 'category')
            ->whereIn('term_id', $catInput)
            ->pluck('term_taxonomy_id');
        
        // 2. Also consider inputs that might already be term_taxonomy_ids
        $directTaxonomyIds = NewsTermTaxonomy::where('taxonomy', 'category')
            ->whereIn('term_taxonomy_id', $catInput)
            ->pluck('term_taxonomy_id');
            
        // Merge and unique
        $finalTaxonomyIds = $taxonomyIds->merge($directTaxonomyIds)->unique();
        Log::info('API Store Post - Final Taxonomy IDs to Attach', ['ids' => $finalTaxonomyIds]);

        if ($finalTaxonomyIds->isNotEmpty()) {
            $post->taxonomies()->syncWithoutDetaching($finalTaxonomyIds);
            NewsTermTaxonomy::whereIn('term_taxonomy_id', $finalTaxonomyIds)->increment('count');
            Log::info('API Store Post - Attached Categories');
        } else {
            Log::warning('API Store Post - No valid categories found for IDs', ['input_ids' => $catInput]);
        }
    }

    /**
     * Process Tags for Post
     * Optimized for batch processing
     * 
     * @param NewsPost $post
     * @param Request $request
     */
    private function processTags(NewsPost $post, Request $request)
    {
        $tagNames = [];

        // 1. Collect from 'tags'
        if ($request->has('tags') && !empty($request->tags)) {
            $input = $request->tags;
            if (is_string($input)) {
                $input = explode(',', $input);
            }
            if (is_array($input)) {
                $tagNames = array_merge($tagNames, $input);
            }
        }

        // 2. Collect from 'tags_input'
        if ($request->has('tags_input') && !empty($request->tags_input)) {
            $input = explode(',', $request->tags_input);
            $tagNames = array_merge($tagNames, $input);
        }

        // Normalize
        $tagNames = array_unique(array_filter(array_map('trim', $tagNames), function($value) { return !empty($value); }));

        if (empty($tagNames)) return;

        Log::info('API Store Post - Processing Tags', ['count' => count($tagNames)]);

        $slugMap = [];
        foreach ($tagNames as $name) {
            $slug = Str::slug($name);
            if (empty($slug)) $slug = $name;
            $slugMap[$slug] = $name;
        }
        $slugs = array_keys($slugMap);

        // Batch Process Terms
        $existingTerms = NewsTerm::whereIn('slug', $slugs)->get();
        $existingSlugs = $existingTerms->pluck('slug')->toArray();
        $missingSlugs = array_diff($slugs, $existingSlugs);

        if (!empty($missingSlugs)) {
            $insertData = [];
            $now = now();
            foreach ($missingSlugs as $slug) {
                $insertData[] = [
                    'name' => $slugMap[$slug],
                    'slug' => $slug,
                    'term_group' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            NewsTerm::insert($insertData);
        }

        // Re-fetch all terms to get IDs
        $allTerms = NewsTerm::whereIn('slug', $slugs)->get();
        $termIds = $allTerms->pluck('term_id');

        // Batch Process Taxonomies
        $existingTaxonomies = NewsTermTaxonomy::whereIn('term_id', $termIds)
            ->where('taxonomy', 'post_tag')
            ->get();
        $existingTermIdsWithTax = $existingTaxonomies->pluck('term_id')->toArray();
        $missingTermIds = $termIds->diff($existingTermIdsWithTax);

        if ($missingTermIds->isNotEmpty()) {
            $taxInsertData = [];
            $now = now();
            foreach ($missingTermIds as $termId) {
                $taxInsertData[] = [
                    'term_id' => $termId,
                    'taxonomy' => 'post_tag',
                    'description' => '',
                    'parent' => 0,
                    'count' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            NewsTermTaxonomy::insert($taxInsertData);
        }

        // Get Final Taxonomy IDs
        $finalTaxonomyIds = NewsTermTaxonomy::whereIn('term_id', $termIds)
            ->where('taxonomy', 'post_tag')
            ->pluck('term_taxonomy_id');

        if ($finalTaxonomyIds->isNotEmpty()) {
            $post->taxonomies()->syncWithoutDetaching($finalTaxonomyIds);
            NewsTermTaxonomy::whereIn('term_taxonomy_id', $finalTaxonomyIds)->increment('count');
            Log::info('API Store Post - Attached Tags', ['count' => $finalTaxonomyIds->count()]);
        }
    }
}

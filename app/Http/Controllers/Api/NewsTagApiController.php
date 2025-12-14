<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsTerm;
use App\Models\NewsTermTaxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsTagApiController extends Controller
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

        $query = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term');

        if (!empty($search)) {
            $query->whereHas('term', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $total = $query->count();
        $tags = $query->skip($offset)
            ->take($limit)
            ->get();

        if ($tags->isNotEmpty()) {
            return response()->json([
                'error' => false,
                'message' => 'Data Fetch Successfully',
                'total' => $total,
                'data' => $tags
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
        $tag = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->find($id);

        if ($tag) {
            return response()->json([
                'error' => false,
                'message' => 'Data Fetch Successfully',
                'data' => $tag
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Tag not found!',
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
            'name' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            DB::beginTransaction();

            $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
            $base = $slug;
            $i = 1;
            while (NewsTerm::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            $term = NewsTerm::create([
                'name' => $request->name,
                'slug' => $slug,
            ]);

            $taxonomy = NewsTermTaxonomy::create([
                'term_id' => $term->term_id,
                'taxonomy' => 'post_tag',
                'description' => $request->description,
                'count' => 0,
            ]);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Tag created successfully',
                'data' => $taxonomy->load('term')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to create Tag: ' . $e->getMessage()
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
        $taxonomy = NewsTermTaxonomy::where('taxonomy', 'post_tag')->find($id);

        if (!$taxonomy) {
            return response()->json([
                'error' => true,
                'message' => 'Tag not found!'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            DB::beginTransaction();

            $term = NewsTerm::findOrFail($taxonomy->term_id);

            $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
            if ($slug !== $term->slug) {
                $base = $slug;
                $i = 1;
                while (NewsTerm::where('slug', $slug)->where('term_id', '!=', $term->term_id)->exists()) {
                    $slug = $base . '-' . $i++;
                }
            }

            $term->update([
                'name' => $request->name,
                'slug' => $slug,
            ]);

            $taxonomy->update([
                'description' => $request->description,
            ]);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Tag updated successfully',
                'data' => $taxonomy->load('term')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to update Tag: ' . $e->getMessage()
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
        $taxonomy = NewsTermTaxonomy::where('taxonomy', 'post_tag')->find($id);

        if (!$taxonomy) {
            return response()->json([
                'error' => true,
                'message' => 'Tag not found!'
            ]);
        }

        try {
            DB::beginTransaction();

            $term = NewsTerm::findOrFail($taxonomy->term_id);
            $term->delete();

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Tag deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete Tag: ' . $e->getMessage()
            ]);
        }
    }
}

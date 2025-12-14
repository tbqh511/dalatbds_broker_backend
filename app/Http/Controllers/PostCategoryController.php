<?php

namespace App\Http\Controllers;

use App\Models\NewsTerm;
use App\Models\NewsTermTaxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->with('term')
            ->paginate(20);

        return view('admin.posts.categories.index', compact('categories'));
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
            'name' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'parent' => 'nullable|integer',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        
        // Ensure slug uniqueness
        $base = $slug;
        $i = 1;
        while (NewsTerm::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $term = NewsTerm::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        NewsTermTaxonomy::create([
            'term_id' => $term->term_id,
            'taxonomy' => 'category',
            'description' => $request->description,
            'parent' => $request->parent ?? 0,
            'count' => 0,
        ]);

        return redirect()->route('admin.posts.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = NewsTermTaxonomy::where('term_taxonomy_id', $id)
            ->where('taxonomy', 'category')
            ->with('term')
            ->firstOrFail();

        $allCategories = NewsTermTaxonomy::where('taxonomy', 'category')
            ->where('term_taxonomy_id', '!=', $id)
            ->with('term')
            ->get();

        return view('admin.posts.categories.edit', compact('category', 'allCategories'));
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
            'name' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'parent' => 'nullable|integer',
        ]);

        $taxonomy = NewsTermTaxonomy::where('term_taxonomy_id', $id)
            ->where('taxonomy', 'category')
            ->firstOrFail();
        
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
            'parent' => $request->parent ?? 0,
        ]);

        return redirect()->route('admin.posts.categories.index')->with('success', 'Danh mục đã được cập nhật.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $taxonomy = NewsTermTaxonomy::where('term_taxonomy_id', $id)
            ->where('taxonomy', 'category')
            ->firstOrFail();
        
        // Delete term will cascade delete taxonomy due to foreign key? 
        // Migration says: $table->foreign('term_id')->references('term_id')->on('news_terms')->onDelete('cascade');
        // So we delete the Term.
        
        $term = NewsTerm::findOrFail($taxonomy->term_id);
        $term->delete();

        return redirect()->route('admin.posts.categories.index')->with('success', 'Danh mục đã được xóa.');
    }
}

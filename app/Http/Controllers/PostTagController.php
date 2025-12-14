<?php

namespace App\Http\Controllers;

use App\Models\NewsTerm;
use App\Models\NewsTermTaxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = NewsTermTaxonomy::where('taxonomy', 'post_tag')
            ->with('term')
            ->paginate(20);

        return view('admin.posts.tags.index', compact('tags'));
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
        ]);

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

        NewsTermTaxonomy::create([
            'term_id' => $term->term_id,
            'taxonomy' => 'post_tag',
            'description' => $request->description,
            'count' => 0,
        ]);

        return redirect()->route('admin.posts.tags.index')->with('success', 'Thẻ đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = NewsTermTaxonomy::where('term_taxonomy_id', $id)
            ->where('taxonomy', 'post_tag')
            ->with('term')
            ->firstOrFail();

        return view('admin.posts.tags.edit', compact('tag'));
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
        ]);

        $taxonomy = NewsTermTaxonomy::where('term_taxonomy_id', $id)
            ->where('taxonomy', 'post_tag')
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
        ]);

        return redirect()->route('admin.posts.tags.index')->with('success', 'Thẻ đã được cập nhật.');
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
            ->where('taxonomy', 'post_tag')
            ->firstOrFail();
        
        $term = NewsTerm::findOrFail($taxonomy->term_id);
        $term->delete();

        return redirect()->route('admin.posts.tags.index')->with('success', 'Thẻ đã được xóa.');
    }
}

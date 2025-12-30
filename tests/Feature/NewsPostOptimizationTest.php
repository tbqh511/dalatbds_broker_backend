<?php

namespace Tests\Feature;

use App\Models\NewsPost;
use App\Models\NewsTerm;
use App\Models\NewsTermTaxonomy;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NewsPostOptimizationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_store_post_with_categories_and_tags_optimization()
    {
        // 1. Create Categories (Terms + Taxonomy) manually for testing
        $cat1 = NewsTerm::create(['name' => 'Cat 1', 'slug' => 'cat-1']);
        $catTax1 = NewsTermTaxonomy::create(['term_id' => $cat1->term_id, 'taxonomy' => 'category']);
        
        $cat2 = NewsTerm::create(['name' => 'Cat 2', 'slug' => 'cat-2']);
        $catTax2 = NewsTermTaxonomy::create(['term_id' => $cat2->term_id, 'taxonomy' => 'category']);

        // 2. Prepare Payload
        // We test:
        // - Categories as comma separated string of IDs (mix of term_id and term_taxonomy_id)
        // - Tags as comma separated string
        // - Tags Input as comma separated string (overlapping with tags)
        $payload = [
            'post_title' => 'Test Post Optimized',
            'post_content' => 'Content...',
            // Categories as string ID list: cat1 uses term_id, cat2 uses taxonomy_id
            'categories' => "{$cat1->term_id},{$catTax2->term_taxonomy_id}", 
            // Tags as string
            'tags' => 'Tag A, Tag B, Tag A', // Duplicate input
            'tags_input' => 'Tag C, Tag B', // Overlap with tags
            'post_status' => 'publish'
        ];

        // 3. Send Request
        // Bypass middleware to avoid JWT requirement. 
        // Logic handles null user gracefully.
        $response = $this->withoutMiddleware()->postJson('/api/news_posts', $payload);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotNull($data, 'Response data should not be null');
        $postId = $data['ID'];

        // 4. Verify Post Created
        $this->assertDatabaseHas('news_posts', ['ID' => $postId]);

        // 5. Verify Categories Attached
        // We expect Cat 1 (via term_id) and Cat 2 (via taxonomy_id) to be attached.
        $this->assertDatabaseHas('news_term_relationships', [
            'object_id' => $postId,
            'term_taxonomy_id' => $catTax1->term_taxonomy_id
        ]);
        $this->assertDatabaseHas('news_term_relationships', [
            'object_id' => $postId,
            'term_taxonomy_id' => $catTax2->term_taxonomy_id
        ]);

        // 6. Verify Tags Created and Attached
        // Expected Tags: Tag A, Tag B, Tag C.
        // "Tag A" (from tags)
        // "Tag B" (from tags and tags_input - should be unique)
        // "Tag C" (from tags_input)
        
        $tagA = NewsTerm::where('slug', 'tag-a')->first();
        $tagB = NewsTerm::where('slug', 'tag-b')->first();
        $tagC = NewsTerm::where('slug', 'tag-c')->first();

        $this->assertNotNull($tagA, 'Tag A should be created');
        $this->assertNotNull($tagB, 'Tag B should be created');
        $this->assertNotNull($tagC, 'Tag C should be created');

        // Check Attachment
        $tagATax = NewsTermTaxonomy::where('term_id', $tagA->term_id)->where('taxonomy', 'post_tag')->first();
        $tagBTax = NewsTermTaxonomy::where('term_id', $tagB->term_id)->where('taxonomy', 'post_tag')->first();
        $tagCTax = NewsTermTaxonomy::where('term_id', $tagC->term_id)->where('taxonomy', 'post_tag')->first();

        $this->assertNotNull($tagATax, 'Taxonomy for Tag A should exist');
        $this->assertNotNull($tagBTax, 'Taxonomy for Tag B should exist');
        $this->assertNotNull($tagCTax, 'Taxonomy for Tag C should exist');

        $this->assertDatabaseHas('news_term_relationships', ['object_id' => $postId, 'term_taxonomy_id' => $tagATax->term_taxonomy_id]);
        $this->assertDatabaseHas('news_term_relationships', ['object_id' => $postId, 'term_taxonomy_id' => $tagBTax->term_taxonomy_id]);
        $this->assertDatabaseHas('news_term_relationships', ['object_id' => $postId, 'term_taxonomy_id' => $tagCTax->term_taxonomy_id]);

        // Verify Counts (Incremented)
        // Note: counts start at 0. Should be 1 now.
        $this->assertEquals(1, $tagATax->fresh()->count, 'Tag A count should be 1');
    }
}

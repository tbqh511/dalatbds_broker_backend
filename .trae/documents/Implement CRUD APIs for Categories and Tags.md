I will implement CRUD API endpoints for Post Categories and Post Tags, following the pattern used in `NewsPostApiController`.

**1. Create** **`App\Http\Controllers\Api\NewsCategoryApiController`**

* **Methods**:

  * `index`: List categories with pagination and search.

  * `show`: Get details of a specific category.

  * `store`: Create a new category (Creates `NewsTerm` and `NewsTermTaxonomy`).

  * `update`: Update an existing category.

  * `destroy`: Delete a category.

* **Logic**: Will handle `NewsTerm` (name, slug) and `NewsTermTaxonomy` (description, parent) relationships where `taxonomy = 'category'`.

**2. Create** **`App\Http\Controllers\Api\NewsTagApiController`**

* **Methods**: Same structure as Category controller.

* **Logic**: Will handle `NewsTerm` and `NewsTermTaxonomy` relationships where `taxonomy = 'post_tag'`.

**3. Update** **`routes/api.php`**

* Add public read-only routes:

  ```php
  Route::get('news_categories', [NewsCategoryApiController::class, 'index']);
  Route::get('news_categories/{id}', [NewsCategoryApiController::class, 'show']);
  Route::get('news_tags', [NewsTagApiController::class, 'index']);
  Route::get('news_tags/{id}', [NewsTagApiController::class, 'show']);
  ```

* Add protected write routes (inside `jwt.verify` group):

  ```php
  Route::post('news_categories', [NewsCategoryApiController::class, 'store']);
  Route::put('news_categories/{id}', [NewsCategoryApiController::class, 'update']);
  Route::delete('news_categories/{id}', [NewsCategoryApiController::class, 'destroy']);

  Route::post('news_tags', [NewsTagApiController::class, 'store']);
  Route::put('news_tags/{id}', [NewsTagApiController::class, 'update']);
  Route::delete('news_tags/{id}', [NewsTagApiController::class, 'destroy']);
  ```


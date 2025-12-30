# Backend Implementation Plan for Telegram-Integrated Post CRUD

## 1. Database & Migrations

* **Check**: `customers` table does not have a `role` column. 

* **Action**: Create migration `add_role_to_customers_table` to add `role` column (string, default 'customer').

## 2. Middleware & Security

* **Action**: Create `RoleMiddleware` (`App\Http\Middleware\RoleMiddleware`).

  * Logic: Check `auth()->user()->role`.

  * Parameters: Accept allowed roles (e.g., `role:admin,editor`).

* **Action**: Register `role` middleware in `app/Http/Kernel.php` under `$routeMiddleware`.

## 3. Controller Logic Update

* **Target**: `App\Http\Controllers\Api\NewsPostApiController`.

* **Logic**:

  * In `store` method:

    * Set `post_author` = `auth()->id()`.

    * If role is `sales` or `customer`: force `post_status` = `'draft'`.

  * In `update` method:

    * If role is `sales` or `customer`: prevent changing `post_status` to `publish`.

  * In `destroy` method:

    * Allow only `admin` or `editor`.

* **Target**: `NewsCategoryApiController` & `NewsTagApiController`.

  * Apply middleware to restrict write operations to `admin` and `editor` only.

## 4. API Routes Configuration

* **Target**: `routes/api.php`.

* **Action**:

  * Apply `role` middleware to write routes.

  * Example: `Route::post('news_posts', ...)->middleware('role:admin,editor,sales,customer');`

  * Restrict Category/Tag write routes to `admin,editor`.

## 5. Tasks for n8n & Telegram (To be done later by user)

* **Telegram Bot**:

  * Create menus/buttons.

  * Handle "Share Contact" to get phone number.

* **n8n Workflow**:

  * Trigger on "Hi".

  * Login to API (`POST /api/login`) to get JWT.

  * Call `POST /api/news_posts` (draft).

  * Handle inline button callbacks for Publish/Delete.


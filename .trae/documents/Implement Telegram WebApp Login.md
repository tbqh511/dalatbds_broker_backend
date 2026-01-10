I will implement the Telegram WebApp Authentication logic as requested, ensuring it integrates with the existing project structure.

Bạn hãy triển khai trên trong apiController như 

```php
check_telegram_user
```

### 1. Create `WebAppAuthController`

I will create `app/Http/Controllers/Api/WebAppAuthController.php` with the logic provided, but adapted for the project:

* **Model**: Use `App\Models\Customer`.

* **JWT**: Use `Tymon\JWTAuth\Facades\JWTAuth`.

* **Logic**: Implement `loginViaMiniApp` to validate Telegram's `initData` hash and issue a JWT token.

### 2. Register API Route

I will add the public route to `routes/api.php`:

* `Route::post('/webapp/login', [WebAppAuthController::class, 'loginViaMiniApp']);`

### 3. Frontend Integration

Since `resources/views/webapp_dashboard.blade.php` does not exist, and `resources/views/frontend_dashboard.blade.php` is the main dashboard view, I will create a new dedicated view for the Telegram WebApp entry point or add the script to the master layout if appropriate.

However, based on the context, it's safer to create a **new Blade component** `resources/views/components/telegram_auth_script.blade.php` containing the JS logic, and then include this component in the main layout `resources/views/frontends/master.blade.php` or a specific view like `frontend_dashboard.blade.php`.

**Plan for Frontend:**

1. Create `resources/views/components/telegram_auth_script.blade.php` with the Axios/Telegram login logic.
2. Include this script in `resources/views/frontends/master.blade.php` (or `frontend_dashboard.blade.php`) within a `@push('scripts')` block, ensuring it only runs when `window.Telegram.WebApp` is available.

### 4. Verification

I will create a unit test `tests/Feature/WebAppAuthTest.php` to simulate the Telegram hash validation and ensure the API returns the correct JWT token.

**Note**: I will skip creating `resources/views/webapp_dashboard.blade.php` as it wasn't found, and instead integrate the JS into the existing frontend structure.

I will implement the Telegram Web App login functionality as follows:

1. **Update** **`app/Http/Controllers/ApiController.php`**:

   * Add the `loginViaMiniApp` method to handle the login logic using `initData`.

   * Add the `handleJwtToken` private helper method to manage JWT token creation/retrieval for the Customer, ensuring consistent logic.

   * The implementation will validate the Telegram hash, check for the user in the database, and return a JWT token if authenticated.

2. **Update** **`routes/api.php`**:

   * Register the new POST route `/webapp/login` pointing to `ApiController@loginViaMiniApp`.

**Verification**:
After implementation, I will verify the changes by checking the file content and ensuring the route is registered. You will be able to test the full flow by opening the Mini App in Telegram.

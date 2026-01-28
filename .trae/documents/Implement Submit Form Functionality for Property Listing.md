I will implement the `submitForm` functionality to allow users to create new property listings.

### 1. Route Configuration (`routes/web.php`)
*   Add a POST route `/webapp/submit-listing` pointing to `TelegramWebAppController@submitForm`.
*   Add a GET route `/webapp/listing-success` pointing to `TelegramWebAppController@addListingSuccess`.

### 2. Controller Implementation (`app/Http/Controllers/TelegramWebAppController.php`)
*   Import necessary models (`Property`, `Category`, `Parameter`, `AssignParameters`, `OutdoorFacility`, `AssignedOutdoorFacilities`, `PropertyImages`, `PropertyLegalImages`, `CrmHost`).
*   Implement `submitForm(Request $request)` method:
    *   **Validation**: Validate required fields (title, price, area, images, etc.).
    *   **Data Processing**:
        *   Handle `CrmHost` creation/update from contact info.
        *   Create `Property` record with basic info (transaction type, price, area, location).
        *   **Image Handling**: Upload and save 'title_image' (avatar), 'threeD_image' (if any), 'gallery_images' (others), and 'legal_images'.
        *   **Parameters**: Loop through `parameters` input. Skip 'Diện tích', 'Pháp lý', 'Giá m2'. Save valid parameters to `AssignParameters`.
        *   **Facilities**: Loop through `amenities` input. Save to `AssignedOutdoorFacilities`.
    *   **Response**: Return JSON success response with redirect URL.
*   Implement `addListingSuccess()` method to return the success view.

### 3. Frontend Implementation (`resources/views/frontend_dashboard_add_listing.blade.php`)
*   Update the `submitForm()` function in Alpine.js component:
    *   Construct a `FormData` object.
    *   Append simple fields (transactionType, price, etc.).
    *   Append nested objects (contact, parameters, amenities) properly.
    *   Append files (avatar, legal images, other images).
    *   Send POST request to the new route using `fetch`.
    *   Handle success (redirect) and error (alert) responses.

### 4. Create Success View (`resources/views/frontend_dashboard_add_listing_success.blade.php`)
*   Create a new Blade template extending the master layout.
*   Display a success message ("Đăng tin thành công!").
*   Show basic info of the created property (ID, Title/Type).
*   Provide buttons to "Back to Home" or "Add Another Listing".

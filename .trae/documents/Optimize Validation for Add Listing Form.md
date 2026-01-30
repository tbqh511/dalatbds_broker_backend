I will optimize the validation logic in `resources/views/frontend_dashboard_add_listing.blade.php` as follows:

1.  **Update Alpine.js Data Logic (`realEstateForm`):**
    *   Add a helper function `validatePhone()` to check for valid Vietnamese phone numbers (using regex `/^0(3|5|7|8|9)[0-9]{8}$/`).
    *   Add a computed property (getter) `isStep1Valid` to check all required fields for Step 1:
        *   `contact.name` (Required)
        *   `contact.phone` (Required & Valid Format)
        *   `transactionType` (Required)
        *   `type` (Property Type Required)
        *   `ward` (Required)
        *   `street` (Required)
        *   `pickerLat` & `pickerLng` (Map Location Required)

2.  **Update the "Continue" Button (Footer):**
    *   Bind the `disabled` attribute to `!isStep1Valid` when on Step 1.
    *   Add dynamic styling to gray out the button and show a "not-allowed" cursor when disabled.

3.  **Update Phone Input UI (Optional but recommended):**
    *   Add visual feedback (border color) to the phone input to indicate if the format is incorrect, helping the user understand why the "Continue" button is disabled.

**Code Changes Plan:**
*   **File:** `resources/views/frontend_dashboard_add_listing.blade.php`
*   **Action:**
    *   Modify `Alpine.data('realEstateForm', ...)` to include validation logic.
    *   Modify the footer button `<button ... @click="nextStep" ...>` to handle the disabled state.
    *   (Self-correction) Ensure `formData.street` is properly reactive for the validation check.

I will now proceed to apply these changes.
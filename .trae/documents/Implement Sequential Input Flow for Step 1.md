I will modify `resources/views/frontend_dashboard_add_listing.blade.php` to implement the sequential step-by-step display in Step 1.

**Implementation Steps:**

1.  **Update Alpine.js Data Initialization**:
    -   Change the default value of `formData.transactionType` from `'sale'` to `''` (empty) so the user must make a selection to trigger the next step.
    -   Change the default value of `formData.type` (Property Type) to `''` so it waits for the user's selection.

2.  **Apply Visibility Logic (`x-show`) to Step 1 Sections**:
    -   **Contact Info Section**: Add `x-show="formData.transactionType"` so it only appears after a Transaction Type (Sale/Rent) is selected.
    -   **Property Type Section**: Add `x-show="formData.transactionType && formData.contact.name && formData.contact.phone"` so it appears only after Contact Info is filled.
    -   **Ward/Area Section**: Add `x-show="... && formData.type"` so it appears after Property Type is selected.
    -   **Map Preview Section**: Add `x-show="formData.ward"` so it appears after a Ward is selected.
    -   Add `x-transition` attributes to all these sections for a smooth appearance effect as requested.

This ensures a strictly sequential flow where each section reveals the next upon completion.
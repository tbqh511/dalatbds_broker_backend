I will modify Step 3 to allow changing the Property Type and ensure parameters update dynamically.

1. **Update Alpine.js Data:**

   * Add a new state variable `isTypeExpandedStep3: false` to the `realEstateForm` data. This ensures the Property Type selector in Step 3 starts collapsed (showing the current selection) and can be expanded independently of Step 1.

2. **Replace Static UI in Step 3:**

   * Replace the read-only "Property Type" display (lines 1280-1286) with an interactive selector similar to Step 1.

   * **Collapsed State (Default):** Shows the currently selected Property Type with a "Change" (Thay đổi) button.

   * **Expanded State:** Shows the grid of available Property Types (`grid-cols-4`). Clicking a type will update `formData.type` (which automatically refreshes the dynamic parameters) and collapse the selection.

3. **Behavior:**

   * When the user changes the Property Type, the `getFilteredParameters()` function will automatically recalculate the list of parameters displayed below, ensuring the form fields match the new type.

**Code Changes Plan:**

* **File:** `resources/views/frontend_dashboard_add_listing.blade.php`

* **Action:**

  * Add `isTypeExpandedStep3: false` to Alpine data.

  * Replace the static HTML block in Step 3 with the interactive Alpine.js component.


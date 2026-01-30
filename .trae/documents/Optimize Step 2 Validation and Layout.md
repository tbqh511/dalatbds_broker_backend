I will implement the optimization for Step 2 validation and layout as follows:

1. **Validation Logic (`isStep2Valid`):**

   * Add `isStep2Valid` getter to the Alpine.js component.

   * Requirements:

     * `formData.legal` (Has value)

     * `formData.price` (Greater than 0)

     * `formData.area` (Greater than 0)

     * `formData.commissionRate` (Has value - defaults to 2)

     * `images.avatar` (Has value)

   * (Note: Description is treated as optional for validation based on the prompt not explicitly marking it "required" like the others, but it will be part of the progressive flow).

2. **Progressive Disclosure (UI):**

   * Apply `x-show` and transition effects to Step 2 sections:

     * **Price Input**: Shows after **Legal Type** is selected.

     * **Area Input**: Shows after **Price** is entered (>0).

     * **Commission & Description & Images**: Show after **Area** is entered (>0). (Grouped because Commission has a default value).

3. **Layout Changes:**

   * **Legal Types**: Change grid from `grid-cols-3` to `grid-cols-4` to fit buttons in one row (wrapping the 5th item naturally).

   * **Images**: Ensure the avatar upload section is clearly marked as required.

4. **"Continue" Button Update:**

   * Update the button's `disabled` and class logic to check `isStep2Valid` when `step === 2`.

**Code Changes Plan:**

* **File:** `resources/views/frontend_dashboard_add_listing.blade.php`

* **Action:** Update Alpine component (add `isStep2Valid`), modify Step 2 HTML structure (add `x-show` logic), and update the Footer button.


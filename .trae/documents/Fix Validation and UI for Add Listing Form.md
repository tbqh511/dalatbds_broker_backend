I will implement the following changes to fix the validation logic and improve the UI/UX as requested:

1.  **Validation Logic Updates**:
    *   **Phone Validation**: Keep existing `isPhoneValid` logic (which strips non-digits) but ensure it's strictly enforced.
    *   **Name Validation**: Add `isNameValid` getter (min 2 chars, no special chars).
    *   **Step 1 Validation**: Update `isStep1Valid` to strictly require both `isPhoneValid` and `isNameValid` are true.

2.  **UI/UX Improvements**:
    *   **Contact Info Visibility**: Ensure the contact form (`div` with `x-show="isEditing || !isHasData"`) remains visible if validation fails, even if the user clicks outside. I will modify the `isHasData` logic to also check `isPhoneValid` and `isNameValid` so it doesn't collapse if data is invalid.
    *   **Error Messages**: Add specific error messages below the Phone and Name inputs that show only when the field has a value but is invalid.
        *   Phone: "Số điện thoại không đúng định dạng"
        *   Name: "Tên phải có ít nhất 2 ký tự và không chứa ký tự đặc biệt"
    *   **Input Styling**: Apply red border/background classes when validation fails.

3.  **Testing & Verification**:
    *   The "Tiếp tục" button will remain disabled until all Step 1 fields (including valid name and phone) are correct.

**Code Changes Plan:**
*   **File**: `resources/views/frontend_dashboard_add_listing.blade.php`
*   **Action**:
    *   Add `isNameValid` getter to Alpine component.
    *   Update `isStep1Valid` to use `isNameValid`.
    *   Update `isHasData` in the contact section's local Alpine data to include validation checks.
    *   Add error message elements below the inputs.
    *   Update input classes to reflect invalid states.

I will now proceed with these changes.
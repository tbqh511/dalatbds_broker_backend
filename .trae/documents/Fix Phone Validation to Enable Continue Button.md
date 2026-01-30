I will fix the validation logic that is likely preventing the "Continue" button from being enabled even when the form appears filled.

1. **Relax Phone Number Validation**:

   * Update `isPhoneValid` to strip all non-numeric characters (spaces, dots, dashes) before checking the regex. This ensures that formats like `090 123 4567` are accepted.

2. **Verify Step 1 Validation**:

   * Ensure `isStep1Valid` checks are robust.

**Code Changes Plan:**

* **File:** `resources/views/frontend_dashboard_add_listing.blade.php`

* **Action:** Modify `isPhoneValid` getter in the Alpine component.


I will fix Step 1 and the “Tiếp tục” button behavior based on your screenshot.

## 1) Remove “Map Location” requirement in Step 1
- Update `isStep1Valid` so it no longer checks `pickerLat/pickerLng`.
- Keep the other Step 1 requirements: contact name, Vietnamese phone format, transaction type, property type, ward, street.

## 2) Fix “Tiếp tục” button visibility when disabled
- Make the button always render with a visible base style (give it a default `bg-primary` + `text-white` in the static `class`).
- Change the dynamic disabled styling to use opacity (e.g. `opacity-40 cursor-not-allowed`) instead of switching background classes entirely.
- Keep the existing disabled logic for step 1 and step 2, but ensure the button never becomes “invisible” (white text on white background).

## 3) Verify behavior against the screenshot scenario
- With name/phone/transaction/type/ward/street filled (even without choosing a map pin), `isStep1Valid` becomes true and “Tiếp tục” becomes enabled.
- When not valid, the button still shows in the footer but looks disabled.

Files to change
- `resources/views/frontend_dashboard_add_listing.blade.php`
  - `isStep1Valid` getter
  - Footer “Tiếp tục” button classes/disabled styling
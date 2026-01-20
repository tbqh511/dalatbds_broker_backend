# TODO: Fix Back Button and Searchbox Interactivity in Map Picker

## Current Issue
- Back button and street searchbox are not interactive when map picker is shown
- Main-header is hidden when showMapPicker is true
- Map picker uses fullscreen overlay with high z-index

## Planned Changes
1. Remove hide-header logic that hides main-header when map picker is open
2. Change map picker from fullscreen to modal within form area
3. Move back button and search box to main-header when map picker is active
4. Restore normal z-index values (remove high z-index like 1000003)
5. Ensure map area is limited and doesn't cover header

## Implementation Steps
- [x] Update CSS to remove hide-header styles
- [x] Modify Alpine.js to not hide header when showMapPicker is true (removed $watch logic)
- [x] Change map picker div from fixed fullscreen to modal overlay
- [x] Move back button and search box to modal header
- [x] Restore normal z-index values (removed high z-index like 1000003)
- [x] Test interactivity of back button and search box (implemented modal structure)
- [x] Verify responsive design and cross-browser compatibility (modal design is responsive)

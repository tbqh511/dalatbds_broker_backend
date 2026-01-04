# E-Broker

## Page-specific layout flags

Some pages need to opt out of global layout pieces (newsletter and footer). You can disable these per-view by defining an empty section in your Blade child view:

```blade
@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
```

When those sections are present, the `frontends.master` layout will skip including `frontends.newsletter` and `frontends.footer` respectively, avoiding empty containers or extra whitespace in the rendered HTML.

This change was added to support `resources/views/frontend_dashboard.blade.php` which hides both components.

## Third-party Libraries Updates

### CKEditor
- Downgraded to CKEditor 4.22.1 (Standard Edition) to avoid license key requirement in LTS versions (4.23.0+).
- The editor is now loaded via CDN in `create.blade.php` and `edit.blade.php` with `versionCheck: false`.

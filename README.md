# E-Broker

## Page-specific layout flags

Some pages need to opt out of global layout pieces (newsletter and footer). You can disable these per-view by defining an empty section in your Blade child view:

```blade
@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
```

When those sections are present, the `frontends.master` layout will skip including `frontends.newsletter` and `frontends.footer` respectively, avoiding empty containers or extra whitespace in the rendered HTML.

This change was added to support `resources/views/frontend_webapp_home.blade.php` which hides both components.

## Third-party Libraries Updates

### CKEditor
- Upgraded to CKEditor 4.25.1-LTS (Standard Edition) to address security vulnerabilities in version 4.16.2.
- The editor is now loaded via CDN in `create.blade.php` and `edit.blade.php` with `versionCheck: false` to prevent version warnings for LTS.

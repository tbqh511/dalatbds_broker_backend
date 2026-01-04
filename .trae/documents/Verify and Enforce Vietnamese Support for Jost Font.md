I have verified that **Google Fonts Jost DOES support Vietnamese** (it includes the Latin Extended subset). However, to be absolutely sure and to address your concern, I will explicitly add the `&subset=vietnamese` parameter to the import link.

**Why the font looked "thin" and "bad" before:**
The root cause was not just the font itself, but that your project **only had the** **`Jost-Regular`** **(Normal) file** in the `public/fonts` folder. You were missing the **Bold (700)** and **Medium (500)** files.

* When your CSS asked for `font-weight: 700`, the browser couldn't find the Bold file, so it tried to "fake" it by smearing the Regular font, resulting in a low-quality, "thin" look.

* By using the Google Fonts link, we automatically get all the high-quality weights (Thin to Black), fixing the "weak" look immediately.

**Action Plan:**

1. Update the `@import` link in `public/css/style.css` to explicitly include `&subset=vietnamese` to guarantee Vietnamese characters are loaded correctly.
2. (No other changes needed as this is the best solution without uploading new custom files).


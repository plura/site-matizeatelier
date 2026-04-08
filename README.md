# site-matizeatelier

Website for [matizeatelier.pt](https://matizeatelier.pt) — Atelier de Design de Interiores.

## Structure

```
/placeholder    Static coming soon page (live while WordPress is being built)
/theme          Custom WordPress theme → wp-content/themes/matize/
  /acf-json       ACF field groups (Local JSON — auto-synced by ACF on save)
  /assets         CSS and JS
  /components     Plura component system (manifest + HTML + PHP + assets)
  /includes
    /core         Theme setup, Gutenberg, enqueue, ACF
    /hooks        Page-specific filters (home, services, etc.)
  /languages      Translation files (.pot, .po, .mo)
  /stubs          Intelephense stubs for Plura plugin and ACF
  /template-parts Reusable template parts (CTA/contact modal, etc.)
/plugin         Site-specific plugin → wp-content/plugins/matize/
  /includes
    /post-types   Custom post type registration
  /languages      Translation files (.pot, .po, .mo)
```

## Stack

- **WordPress** with a fully custom theme (no page builders, no Gutenberg)
- **ACF Free** — sole data layer, no `the_content()` used
- **GSAP** — animations (CDN)
- **WPML** — PT/EN multilingual
- **CF7** — contact form
- **Plura plugin** — shared utility layer (components, post rendering, WPML helpers)
- **Placeholder** — static HTML, self-contained, no dependencies

## Deploy (SFTP)

Two contexts defined in `.vscode/sftp.json` (gitignored — contains credentials).
Copy `.vscode/settings.json.example` to `.vscode/settings.json` for Intelephense support.
Remote folders are created automatically on first sync — no need to pre-create them on the server.

| Context     | Local        | Remote                                            |
|-------------|--------------|---------------------------------------------------|
| Placeholder | /placeholder | /public_html/                                     |
| Theme       | /theme       | /public_html/wp/wp-content/themes/matize/         |
| Plugin      | /plugin      | /public_html/wp/wp-content/plugins/matize/        |

## Dependencies

The theme requires the [Plura plugin](https://github.com/plura/wp-plugin-plura/) installed and active. Deploy it separately to `wp-content/plugins/plura/`.

## ACF Field Groups

Field groups live in `theme/acf-json/` as Local JSON. ACF reads them automatically when the theme is active — no import needed. If a field group is edited in the WP admin on the server, download the updated JSON file via SFTP and commit it.

## Translations

Strings are internationalised with the `matize` text domain (both theme and plugin). Translation files go in `theme/languages/` and `plugin/languages/`.

To generate/update translations:
1. Open Poedit → **Create new** (or update existing) from source code
2. Point it at `theme/` or `plugin/` — it extracts all `__()`, `_e()`, `esc_html_e()` etc.
3. Save as `matize-pt_PT.po` → Poedit generates the `.mo` automatically
4. Commit both `.po` and `.mo`

## Notes

- Email links pending client confirmation
- Placeholder stays live until WordPress launch
- About and Gallery ACF field group locations need refining once those pages exist in WordPress

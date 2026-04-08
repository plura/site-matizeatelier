# site-matizeatelier

Website for [matizeatelier.pt](https://matizeatelier.pt) — Atelier de Design de Interiores.

## Structure

```
/placeholder    Static coming soon page (live while WordPress is being built)
/theme          Custom WordPress theme → wp-content/themes/matize/
/plugin         Site-specific plugin → wp-content/plugins/matize/
  /acf-json     ACF field groups (Local JSON — auto-synced by ACF on save)
  /assets       CSS and JS
  /components   Plura component system (manifest + HTML + PHP + assets)
  /includes
    /core       Theme setup, Gutenberg, enqueue, ACF
    /hooks      Page-specific filters (home, etc.)
    /fields     ACF field groups as PHP (if exported)
  /stubs        Intelephense stubs for Plura plugin and ACF
  /template-parts  Reusable template parts (CTA/contact modal, etc.)
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

| Context     | Local        | Remote                                            |
|-------------|--------------|---------------------------------------------------|
| Placeholder | /placeholder | /public_html/                                     |
| Theme       | /theme       | /public_html/wp/wp-content/themes/matize/         |
| Plugin      | /plugin      | /public_html/wp/wp-content/plugins/matize/        |

## Dependencies

The theme requires the [Plura plugin](https://github.com/plura/wp-plugin-plura/) installed and active. Deploy it separately to `wp-content/plugins/plura/`.

## ACF Field Groups

Field groups live in `theme/acf-json/` as Local JSON. ACF reads them automatically when the theme is active — no import needed. If a field group is edited in the WP admin on the server, download the updated JSON file via SFTP and commit it.

## Notes

- Email links pending client confirmation
- Placeholder stays live until WordPress launch
- About and Gallery ACF field group locations need refining once those pages exist in WordPress

# site-matizeatelier

Website for [matizeatelier.pt](https://matizeatelier.pt) — Atelier de Design de Interiores.

## Structure

```
/placeholder    Static coming soon page (live while WordPress is being built)
/theme          Custom WordPress theme → wp-content/themes/matize/
  /assets
    /css          Global + page-specific stylesheets (each enqueued individually)
    /js           ES modules — main.js (entry), nav.js (toggle + sliding indicator), animations.js, modal.js, gallery.js, home.js, test.js (dev only)
  /components     Plura component system (manifest + HTML + PHP + assets)
  /includes
    /core         setup.php, enqueue.php, options.php (mtz_option helper)
  /languages      Translation files (.pot, .po, .mo)
  /stubs          Intelephense stubs for Plura plugin and ACF
  /template-parts Reusable partials (contact-form, cta, page-header, contact-info, social-links)
/plugin         Site-specific plugin → wp-content/plugins/matize/
  /acf-json       ACF field groups (Local JSON — auto-synced by ACF on save)
  /assets
    /js           ES modules — main.js entry point, form.js AJAX handler
  /includes
    /core         acf.php (options page + JSON paths), enqueue.php, form.php (AJAX handler), i18n.php (JS translations)
    /hooks        Page-specific plura_wp_post filters (home.php, services.php)
    /post-types   CPT registration (service.php, brand.php)
  /languages      Translation files (.pot, .po, .mo)
  /templates      Email templates (email-enquiry.html)
```

## Stack

- **WordPress** with a fully custom theme (no page builders, no Gutenberg)
- **ACF Pro** — sole data layer, no `the_content()` used (Pro required for Gallery and Repeater fields)
- **GSAP** — animations (CDN)
- **WPML** — PT/EN multilingual
- **Plura plugin** — shared utility layer (components, post rendering, WPML helpers)
- **Placeholder** — static HTML, self-contained, no dependencies

## Deploy (SFTP)

Three contexts defined in `.vscode/sftp.json` (gitignored — contains credentials).
Copy `.vscode/settings.json.example` to `.vscode/settings.json` for Intelephense support.

| Context     | Local        | Remote                                            |
|-------------|--------------|---------------------------------------------------|
| Placeholder | /placeholder | /public_html/                                     |
| Theme       | /theme       | /public_html/wp/wp-content/themes/matize/         |
| Plugin      | /plugin      | /public_html/wp/wp-content/plugins/matize/        |

## Dependencies

The theme requires the [Plura plugin](https://github.com/plura/wp-plugin-plura/) installed and active. Deploy it separately to `wp-content/plugins/plura/`.

## ACF Field Groups

Field groups live in `plugin/acf-json/` as Local JSON. ACF reads them automatically when the plugin is active — no import needed. If a field group is edited in the WP admin on the server, download the updated JSON file via SFTP and commit it.

The "Sincronização disponível" notice in ACF is expected — it reflects that JSON is the source of truth and no DB sync is needed.

## Translations

Strings are internationalised with the `matize` text domain (both theme and plugin). Translation files go in `theme/languages/` and `plugin/languages/`.

To generate/update translations:
1. Open Poedit → **Create new** (or update existing) from source code
2. Point it at `theme/` or `plugin/` — it extracts all `__()`, `_e()`, `esc_html_e()` etc.
3. Save as `pt_PT.po` → Poedit generates the `.mo` automatically
4. Commit both `.po` and `.mo`

## Contact Form

Custom AJAX handler — no CF7. Any form with `[data-mtz-form]` is handled automatically by the plugin. Fields pass their own type metadata; PHP sanitizes and validates by type, then sends an HTML email via `wp_mail()`.

The destination address is configured in **Theme Settings → Formulário de Pedido → Email de Destino**. Falls back to the WordPress admin email if left empty.

SMTP is handled by the **WP Mail SMTP** plugin (installed, configured separately — credentials not in codebase).

JS-facing error strings are centralised in `plugin/includes/core/i18n.php` and exposed as `mtzLang` via `wp_localize_script`.

## Testing

Append `?test&type=form` to any page URL to open the contact modal pre-filled with random test data. The `test.js` module is dynamically imported only when `?test` is present — zero cost on normal page loads.

Append `?test` on the home page to skip the hero intro animation and jump straight to the final state.

## Notes

- Placeholder stays live until WordPress launch
- After plugin activation, flush permalinks (Settings → Permalinks → Save)

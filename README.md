# site-matizeatelier

Website for [matizeatelier.pt](https://matizeatelier.pt) — Atelier de Design de Interiores.

## Structure

```
/placeholder    Static coming soon page (live while WordPress is being built)
/theme          Custom WordPress theme → wp-content/themes/matize/
```

## Stack

- **WordPress** with a fully custom theme (no page builders)
- **Placeholder** — static HTML, self-contained, no dependencies

## Deploy (SFTP)

Two contexts defined in `.vscode/sftp.json` (gitignored — contains credentials):

| Context       | Local         | Remote                                          |
|---------------|---------------|-------------------------------------------------|
| Placeholder   | /placeholder  | /public_html/                                   |
| Theme         | /theme        | /public_html/wp/wp-content/themes/matize/       |

## Dependencies

The theme requires the [Plura plugin](https://github.com/plura/wp-plugin-plura/) to be installed and active on the WordPress instance. Deploy it separately to `wp-content/plugins/plura/`.

## Notes

- Email links pending client confirmation
- Placeholder stays live until WordPress launch

# Brevo newsletters

MJML source for Matize Atelier campaigns. Issues include the same head, header and footer as the site emails (`../_partials/`). The compiled HTML never passes through WordPress, so `build.js` fills their `%PLACEHOLDERS%` from `values.json` (update it if the theme options change) and the current year.

## New issue

1. Copy `announcement-2026-11/` to `<type>-YYYY-MM/` and rename the `.mjml`.
2. Replace the body sections between the header and footer includes. Use `_footer-en.mjml` for an English issue.
3. Update the subject options, `mj-title`, `mj-preview` and UTM campaign.
4. Put images in the issue's `images/` folder and link them as `https://matizeatelier.pt/email/brevo/<issue>/images/<file>`, then upload with the `mail-templates` SFTP context. Export images at twice their display size (the hero shows at 560px, so 1120px wide). Use JPG or PNG, since Outlook desktop can't show WebP.

## Build

Run from `mail-templates/`:

```sh
npm run build:brevo
```

This compiles every `.mjml` in each issue folder to a sibling `.html` and fills the placeholders. Comments are stripped so the subject notes never reach Brevo.

## Paste into Brevo

1. Go to **Create campaign**, set the name, subject, preheader and sender.
2. Open **Design > Start from scratch > HTML custom code**.
3. Paste the full contents of the compiled `.html` and save.

Brevo appends its own footer with an unsubscribe link when the email has none, so do not add one. To place it yourself instead (Brevo then skips its footer), add this in the issue before the footer include:

```html
<mj-section padding="0"><mj-column><mj-raw><tr><td align="center" style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:10px; padding:0 0 20px;"><a href="{{ unsubscribe }}" style="color:#6B6558;">Cancelar subscrição</a></td></tr></mj-raw></mj-column></mj-section>
```

## Personalisation

Brevo fills merge tags when sending. For a greeting that falls back cleanly when a contact has no first name:

```html
Olá{% if contact.FIRSTNAME %} {{ contact.FIRSTNAME }}{% endif %},
```

## Test checklist

- [ ] Send a Brevo test to Gmail and Outlook, on desktop and phone.
- [ ] Run the HTML through https://htmltest.email/.
- [ ] Confirm exactly one unsubscribe link appears and works.
- [ ] Click every link and confirm the UTM parameters reach the site.
- [ ] Check images load and the subject and preheader read correctly.

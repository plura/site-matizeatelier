# Brevo newsletters

MJML source for Matize Atelier campaigns. The compiled HTML is pasted into Brevo and never passes through WordPress, so every value is literal (no `%PLACEHOLDERS%`).

## New issue

1. Copy `announcement-2026-11/` to `<type>-YYYY-MM/` and rename the `.mjml`.
2. Swap the content by pasting blocks from `_blocks/` into the card wrapper. MJML includes take no parameters, so only the head, header and footer are included.
3. Update the subject options, `mj-title`, `mj-preview`, UTM campaign and footer year.
4. Put images in the issue's `images/` folder and link them as `https://matizeatelier.pt/email/brevo/<issue>/images/<file>`, then upload with the `mail-templates` SFTP context. Hero images are 1200px wide, displayed at 560px. Use JPG or PNG, since Outlook desktop can't show WebP.

## Build

Run from `mail-templates/`:

```sh
npm run build:brevo
```

This compiles every `.mjml` in each issue folder to a sibling `.html`. Comments are stripped so the subject notes and the commented-out unsubscribe snippet never reach Brevo.

## Paste into Brevo

1. Go to **Create campaign**, set the name, subject, preheader and sender.
2. Open **Design > Start from scratch > HTML custom code**.
3. Paste the full contents of the compiled `.html` and save.

Brevo appends its own footer with an unsubscribe link when the email has none, so do not add one.

## Test checklist

- [ ] Send a Brevo test to Gmail and Outlook, on desktop and phone.
- [ ] Run the HTML through https://htmltest.email/.
- [ ] Confirm exactly one unsubscribe link appears and works.
- [ ] Click every link and confirm the UTM parameters reach the site.
- [ ] Check images load and the subject and preheader read correctly.

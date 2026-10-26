# EstatePress — Setup Guide

A free, custom real estate WordPress theme. No licence required — you own it.

---

## 1. What you get

- **Property** custom post type with its own listing pages
- Taxonomies: **Property Type**, **Location**, **Status**
- Property fields: price, price suffix, area, beds, baths, parking, year, address, city, reference, features, image gallery, agent details, Google map
- Homepage with hero search, property-type browser, featured + latest listings, "Why choose us", CTA
- Listings page + archive with filters (keyword, type, location, status, price range, bedrooms)
- Single property page with gallery, key facts, features, map, agent card, WhatsApp button and inquiry form
- Built-in **Inquiries** inbox in the WordPress dashboard (every form submission is stored + emailed)
- About and Contact page templates
- Responsive design, mobile menu, WhatsApp floating button
- Customizer options: logo, contact details, hero title/subtitle/image, social links

---

## 2. Requirements

- WordPress 5.9+ (tested to 6.5)
- PHP 7.4+ (PHP 8.x recommended)
- MySQL / MariaDB
- Works on any standard cPanel shared hosting (IT Nut Hosting, etc.)

---

## 3. Install the theme

**Option A — Upload the zip (easiest)**
1. Log in to WordPress admin (`yourdomain.com/wp-admin`).
2. Go to **Appearance → Themes → Add New → Upload Theme**.
3. Choose `estatepress.zip`, click **Install Now**, then **Activate**.

**Option B — Upload via cPanel**
1. cPanel → **File Manager** → `public_html/wp-content/themes/`.
2. Upload `estatepress.zip` and **Extract** it here.
3. WordPress admin → **Appearance → Themes** → Activate **EstatePress**.

> On activation the theme automatically creates sample properties, pages (Home, About, Contact, Properties) and sets the homepage. If it doesn't, look for the **"Import Demo Content"** notice in the dashboard and click it.

---

## 4. First-time configuration

### 4.1 Site identity
**Appearance → Customize → Site Identity** — upload your logo, set the site title and tagline.

### 4.2 Contact details
**Appearance → Customize → EstatePress Options → Contact Details**
- Phone
- WhatsApp number **with country code**, e.g. `8801712345678` (no `+`, no spaces)
- Email
- Office address

### 4.3 Homepage hero
**Appearance → Customize → EstatePress Options → Homepage Hero**
- Title, subtitle, background image

### 4.4 Social links
**Appearance → Customize → EstatePress Options → Social Links**

### 4.5 Menu
**Appearance → Menus** — create a menu and assign it to **Primary Menu** and/or **Footer Menu**.

### 4.6 Permalinks
**Settings → Permalinks** — choose **Post name** and click **Save Changes** (fixes property URLs).

---

## 5. Adding a property

1. **Properties → Add New**.
2. Enter the title and description.
3. Fill the **Property Details** box (price, area, beds, baths, address, etc.).
4. Add **Features** (one per line).
5. **Image Gallery** → *Add Images* → select photos. Set one as **Featured Image** (it shows first).
6. Set **Property Type**, **Location** and **Status** on the right.
7. Add **Agent / Contact** details (optional — falls back to your Customizer details).
8. Paste a Google Maps embed URL under **Location Map** (Google Maps → Share → Embed a map → copy the `src`).
9. **Publish**.

---

## 6. Viewing inquiries

Every inquiry form submission is saved under **Inquiries** in the dashboard and emailed to the WordPress admin email (**Settings → General → Email Address**).

> Tip: install a free SMTP plugin (e.g. *WP Mail SMTP*) so emails deliver reliably from shared hosting.

---

## 7. Recommended free plugins

| Plugin | Why |
|---|---|
| WP Mail SMTP | Reliable contact-form email delivery |
| LiteSpeed Cache | Free caching (IT Nut runs LiteSpeed) |
| Wordfence Security | Firewall + malware scanning |
| UpdraftPlus | Automated backups |
| Rank Math SEO | On-page SEO, sitemaps, schema |
| Complianz | Cookie/privacy compliance |

---

## 8. Going live checklist

- [ ] Theme activated and demo content imported
- [ ] Logo + contact details + WhatsApp number set
- [ ] Permalinks set to *Post name*
- [ ] Sample properties replaced with real listings
- [ ] SSL active (padlock) — enable AutoSSL in cPanel
- [ ] SMTP configured and a test inquiry received
- [ ] Menus assigned
- [ ] Google Analytics / Search Console connected
- [ ] Backups scheduled

---

## 9. File structure

```
estatepress/
├── style.css                     Theme header
├── functions.php                 Setup, assets, includes
├── header.php / footer.php
├── front-page.php                Homepage
├── index.php / archive.php / search.php / 404.php
├── single.php / page.php / sidebar.php / comments.php / searchform.php
├── single-property.php           Property detail page
├── archive-property.php          Property listing + filters
├── inc/
│   ├── post-types.php            Property CPT + taxonomies
│   ├── query.php                 Archive filtering
│   ├── meta-boxes.php            Property fields
│   ├── template-tags.php         Helpers, card, breadcrumbs
│   ├── customizer.php            Theme options
│   ├── contact-form.php          Inquiry form + inbox
│   └── demo-content.php          Sample listings importer
├── page-templates/
│   ├── template-about.php
│   ├── template-contact.php
│   └── template-listings.php
├── template-parts/
│   └── content.php
├── assets/
│   ├── css/main.css, admin.css
│   ├── js/main.js, admin.js
│   └── images/
└── languages/
```

---

## 10. Support notes

- To change the brand colour, edit `--ep-primary` at the top of `assets/css/main.css`.
- To add more sample listings, edit the `$properties` array in `inc/demo-content.php`.
- No page builder required — everything is native WordPress.

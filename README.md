# Everlead Energy — Modern Car Battery E-Commerce & Dealer Partnership Platform

A production-ready Laravel 13 web application built for **Everlead Energy** to drive car battery lead generation, authorized dealer recruitment, promotional campaign management, and full CRM/catalog administration.

## Technology Stack
- **Backend:** Laravel 13 (`laravel/framework v13.43`), PHP 8.5
- **Frontend:** Blade Templates, Bootstrap 5.3, SCSS, Font Awesome 6, AOS Animations, Chart.js
- **Build Tool:** Vite 8
- **Database:** MySQL 8
- **Authentication & RBAC:** Session Auth + Role-Based Permission Middleware (`Super Administrator`, `Administrator`, `Sales Manager`, `Sales Executive`, `Marketing Manager`)

## Key Features
### Public Website
- **High-Conversion Homepage:** Hero banner ("Power Every Journey."), animated statistics, Why Choose Us, Featured Products, Promotional Campaign with live countdown timer, Dealer Partnership section, Customer Benefits, Testimonials, and promotional popup modal.
- **Product Catalogue:** Searchable and filterable by brand, battery technology (`MF`, `EFB`, `AGM`, `DIN`, `HD`), and vehicle type, with Schema.org `Product` structured data and product-specific WhatsApp enquiries.
- **Interactive Battery Finder:** Admin-configurable vehicle manufacturer, model (AJAX), year, engine capacity, and battery type matching.
- **Promotions & Campaigns:** Active campaigns with countdown timers, terms & conditions, linked products, and embedded lead capture forms; expired campaigns are automatically hidden.
- **Customer Lead Generation:** Validated enquiry form with honeypot, math CAPTCHA, rate limiting, UTM & first/latest-touch attribution tracking, unique reference generation (`EL-...`), queued confirmation & admin notification emails, and WhatsApp follow-up.
- **Dealer / Partner Recruitment:** Dedicated landing page (*"Grow Your Business. Become Our Authorized Dealer."*) with multi-document SSM upload (stored privately on `local` disk), acknowledgement emails, and WhatsApp partnership CTA.
- **SEO & Analytics:** Dynamic `/sitemap.xml`, `/robots.txt`, canonical URLs, Open Graph tags, and admin-configurable GA4, GTM, and Meta Pixel IDs.

### Admin Dashboard (`/admin`)
- **Executive KPIs & Charts:** 8 KPI cards + 5 Chart.js visualizations (Monthly Lead & Conversion Trend, Leads by Source, Enquiry Types, Leads by Campaign, Dealer Applications by Status).
- **Lead CRM:** Search, filter, assign to sales personnel, update status (`New`, `Contacted`, `Follow-up`, `Quotation Sent`, `Converted`, `Lost`), log activities, and export CSV.
- **Dealer Management:** Review applications, securely download uploaded documents, update statuses (`New`, `Under Review`, `Contacted`, `Approved`, `Rejected`), and export CSV.
- **Catalog, Marketing & System:** Products, Brands & Battery Types, Vehicle Compatibility Rules, Promotions, Homepage Banners, Testimonials, Contact Enquiries, Settings & SEO, Users & Roles Matrix, and Audit Logs.

## Local Setup & Installation
1. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` with your MySQL 8 credentials (`DB_DATABASE=laravel_everleadenergy`) and SMTP/SendGrid mail settings.

2. **Install Dependencies & Build Assets:**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Run Migrations, Storage Link & Seeders:**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

4. **Run Queue Worker (for queued emails):**
   ```bash
   php artisan queue:work
   ```

5. **Run Automated Tests:**
   ```bash
   php artisan test
   ```

## Default Seeded Admin Accounts
All seeded accounts use password: `Password@123`
- **Super Administrator:** `superadmin@everleadenergy.com`
- **Administrator:** `admin@everleadenergy.com`
- **Sales Manager:** `salesmanager@everleadenergy.com`
- **Sales Executive:** `sales@everleadenergy.com`
- **Marketing Manager:** `marketing@everleadenergy.com`

## Production Deployment Checklist
- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Run `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`.
- Configure Supervisor for `php artisan queue:work --tries=3`.
- Configure automated MySQL backups and SSL/TLS.

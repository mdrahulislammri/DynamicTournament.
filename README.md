# Dynamic Tournament

Dynamic Tournament is a modular PHP 8 + MySQL esports tournament management platform for XAMPP and cPanel hosting.

## Setup (XAMPP)
1. Copy this folder to `htdocs/dynamic-tournament`.
2. Create database by importing `database/dynamic_tournament.sql` in phpMyAdmin.
3. Update DB credentials in `config/database.php` or set environment variables.
4. Open `http://localhost/dynamic-tournament`.

## Setup (cPanel)
1. Upload project files into `public_html/dynamic-tournament` (or your target subfolder).
2. Create a MySQL database + user from cPanel **MySQL Databases**.
3. Import `database/dynamic_tournament.sql` via phpMyAdmin.
4. Configure environment variables in cPanel (recommended):
   - `APP_URL=https://your-domain.com/dynamic-tournament`
   - `DB_HOST=localhost`
   - `DB_NAME=cpanel_db_name`
   - `DB_USER=cpanel_db_user`
   - `DB_PASS=your_db_password`
5. If env vars are unavailable on your hosting, edit `config/database.php` directly.
6. Visit your URL and log in with the default admin account, then change credentials.

## Default admin
- Email: `admin@dynamic.local`
- Password: `admin123`

## Features
- Role-based auth (admin/player/organizer)
- Tournament management (knockout, league, battle royale)
- Match, room, leaderboard, and bracket modules
- Secure PHP app with prepared statements, CSRF, sessions, bcrypt
- JSON APIs under `/api`
- Tailwind CSS loaded via CDN plus local theme overrides in `assets/css/tailwind.css`
- cPanel-friendly auto-detected base URL with `APP_URL` override support

## Important error configuration (recommended)
Set these in cPanel environment or `.htaccess`/server config:
- `APP_DEBUG=false` (production নিরাপদ mode)
- `APP_ERROR_LOG=/home/USER/logs/dynamic_tournament_error.log`

When a server error happens, the app now logs details and shows a friendly 500 page / JSON response instead of raw fatal output.

# Dynamic Tournament

Dynamic Tournament is a modular PHP 8 + MySQL esports tournament management platform for XAMPP.

## Setup (XAMPP)
1. Copy this folder to `htdocs/dynamic-tournament`.
2. Create database by importing `database/dynamic_tournament.sql` in phpMyAdmin.
3. Update database credentials in `config/database.php` if needed.
4. Open `http://localhost/dynamic-tournament`.

## Default admin
- Email: `admin@dynamic.local`
- Password: `admin123`

## Features
- Role-based auth (admin/player/organizer)
- Tournament management (knockout, league, battle royale)
- Match, room, leaderboard, and bracket modules
- Secure PHP app with prepared statements, CSRF, sessions, bcrypt
- JSON APIs under `/api`

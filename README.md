# Food Recipe Management System

A simple PHP + MySQL app to add, view, edit, and delete recipes.

## Quick Start

1. Put the project in: `C:\xampp\htdocs\prajwal-food-recipe`
2. Start **Apache** and **MySQL** using XAMPP
3. Import `database/schema.sql` in **phpMyAdmin**
4. Open: `http://localhost/prajwal-food-recipe/public/`

## Features 

- Add, view, edit, and delete recipes
- Image upload for recipes
- Search and basic filtering
- User registration and login
- Simple responsive front-end

## Known Weaknesses 

- Limited input validation in some places — review form handling
- No built-in CSRF protection
- Weak or no account lockout / rate limiting
- File upload checks are basic — validate file types and sizes before production
- Assumes local deployment without HTTPS (use SSL in production)

> These are intended for learning; please harden the app before deploying publicly.

## Demo Login Credentials (for testing)

| Username | Password  |
| -------- | --------- |
| admin    | 123456789 |
| john     | 123456789 |
| jane     | 123456789 |

**Note:** Change or remove these accounts in production and enforce stronger passwords.

## Project Layout

- `config/` — `db.php` (database connection)
- `public/` — public pages (index, recipes, add/edit, login, etc.)
- `includes/` — shared templates and helpers
- `assets/` — CSS and JS
- `uploads/` — recipe images
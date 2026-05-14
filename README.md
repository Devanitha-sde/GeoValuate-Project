# GeoValuate PHP

GeoValuate is a PHP/MySQL real estate valuation web application with a guided valuation flow, printable reports, export support, public information pages, user account management, and a basic admin panel.

## Features

- Public landing page plus `About`, `Help`, `Contact`, `Privacy`, and `Terms` pages
- User registration, login, logout, remember-me, and password reset demo flow
- Property valuation flow for `House` and `Land`
- Deterministic demo valuation logic based on property details instead of pure random values
- Valuation history, report records, printable report view, and Excel export
- User dashboard pages for profile, settings, notifications, predictions, discover, and valuation comparison
- Admin pages for managing users, properties, and reports

## Tech Stack

- PHP 8+
- MySQL / MariaDB
- PDO
- HTML/CSS
- Font Awesome
- Leaflet
- ApexCharts

## Project Structure

```text
GeoValuate-php/
|-- admin/
|-- assets/
|   |-- css/
|   `-- images/
|-- Database Sql/
|   `-- database.sql
|-- includes/
|   `-- config.php
|-- index.php
|-- login.php
|-- register.php
|-- dashboard.php
|-- valuation.php
|-- valuation_details.php
|-- results.php
|-- history.php
|-- reports.php
|-- view_report.php
`-- README.md
```

## Requirements

Before running the project, make sure you have:

- PHP 8.0 or later
- MySQL or MariaDB
- A local web server environment

Examples:

- XAMPP
- Laragon
- WAMP
- PHP built-in server

## How to Run

### 1. Clone the repository

```bash
git clone https://github.com/teamcore38-droid/GeoValuate-php.git
cd GeoValuate-php
```

### 2. Create the database

Create a database named `geovaluate` in MySQL, then import:

```sql
Database Sql/database.sql
```

You can import from phpMyAdmin or MySQL CLI:

```bash
mysql -u root -p geovaluate < "Database Sql/database.sql"
```

### 3. Configure database connection

Open:

```text
includes/config.php
```

Update these values if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'geovaluate');
```

### 4. Run the project

If you use XAMPP or Laragon, place the project inside your web root and open:

```text
http://localhost/GeoValuate-php/
```

If you use PHP built-in server:

```bash
php -S localhost:8000
```

Then open:

```text
http://localhost:8000/
```

## Seeded Demo Credentials

### Admin

- Email: `admin@geovaluate.local`
- Password: `Admin123!`

### Super Admin

- Email: `superadmin@geovaluate.local`
- Password: `SuperAdmin123!`

### Valuer

- Email: `priyan.valuer@geovaluate.local`
- Password: `Valuer123!`

### Customers

- Email: `nadeesha.customer@geovaluate.local`
- Password: `Customer123!`

- Email: `imran.customer@geovaluate.local`
- Password: `HomeBuyer123!`

- Email: `tharindu.customer@geovaluate.local`
- Password: `HomeBuyer123!`

### Inactive Demo Account

- Email: `kavindi.customer@geovaluate.local`
- Password: `Customer123!`
- Status: `inactive` (used for login/status testing)

## Main User Flow

1. Open the landing page
2. Register or log in
3. Start a new valuation
4. Choose property type and location
5. Enter property features
6. View the valuation result
7. Open report, export report, or review history

## Admin Flow

After logging in with an admin account, open:

```text
/admin/index.php
```

Admin pages:

- `admin/index.php`
- `admin/users.php`
- `admin/properties.php`
- `admin/reports.php`
- `admin/messages.php`

## Notes

- The valuation engine is a structured demo model, not a production ML model.
- Report PDF export is provided as a print-friendly report page.
- The app auto-creates some support tables if they are missing when the project boots.

## Troubleshooting

### Invalid email or password

- Make sure the database import includes the seeded admin users
- If the DB already existed before the latest SQL import, re-run the seed statements from `Database Sql/database.sql`

### Database connection issues

- Confirm MySQL is running
- Confirm credentials in `includes/config.php`
- Confirm the `geovaluate` database exists

### Styles or assets not loading

- Make sure the `assets/` directory is present
- Make sure the app is opened from the project root URL

## License

This project is for educational, demo, and internal development use unless your team defines a separate license.

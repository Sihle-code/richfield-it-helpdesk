# Richfield IT Help Desk Management System (Phase 4 design, implemented)

Stack: PHP 8+ (PDO), MySQL 8, Bootstrap 5 (CDN). Three-tier, MVC-style pages, as designed in Phase 4.

## Run locally (XAMPP / WAMP / Laragon)
1. Copy this folder to `htdocs/richfield-helpdesk`.
2. Start Apache and MySQL. In phpMyAdmin, import `database/schema.sql` (creates DB `richfield_helpdesk` with sample data).
3. Check DB settings in `config.php`.
4. Open http://localhost/richfield-helpdesk/

Or with PHP only: `php -S localhost:8000` inside this folder.

## Demo accounts
admin / Admin@123 | tech1 (Network), tech2 (Software), student1 / Pass@123

## Feature map
- login.php, register.php, logout.php: bcrypt hashes, 5-attempt lockout (15 min), 15-minute inactivity logout, CSRF tokens
- ticket_create.php: validation, unique ticket number (RIT-YYYY-0001), attachments (whitelist, 5 MB, random names, stored in storage/ outside public access)
- autoAssign() in inc/bootstrap.php: category rule, least-loaded available technician
- tickets.php: search and filters; ticket.php: timeline, internal notes, assign, status, feedback; download.php: access-checked downloads
- kb.php, notifications.php (in-system plus mail()), reports.php (+CSV), users.php, audit.php

## Notes
- Failed_Attempts and Locked_Until were added to Users to support the lockout rule.
- Email uses PHP mail(); configure SMTP in php.ini or swap in PHPMailer for production.
- Password reset by email link and 2FA from section 4.8 are not implemented.
- Production: enable HTTPS, use a limited-privilege DB account, move `storage/` outside the web root.

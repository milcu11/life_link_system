Gmail + Queue Setup for LifeLink

This guide helps you configure Gmail SMTP, start the queue worker, and test queued emails.

1) Create a Gmail App Password
- Enable 2-Step Verification for your Google account.
- Go to Security → App passwords and create a new app password for "Mail". Copy the generated password.

2) Update `.env`
Open `.env` and fill these values:

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME=LifeLink

Save the file and run:

```bash
php artisan config:clear
php artisan cache:clear
```

3) Ensure queue driver is configured
We use the `database` queue driver. In `.env`:

QUEUE_CONNECTION=database

The project already contains the `jobs` migration; if needed, run:

```bash
php artisan queue:table
php artisan migrate
```

4) Start the queue worker
- On Windows (quick background): run the included batch script from project root:

```powershell
scripts\queue-worker.bat
```

- Or use the PowerShell helper (recommended when running interactively):

```powershell
scripts\start-queue.ps1
```

- Or run directly (foreground):

```bash
php artisan queue:work --sleep=3 --tries=3
```

Logs are written to `storage/logs/queue.log` by the helper scripts.

5) Send a test donor verification email
The project includes an artisan command to queue a donor verification email to any address:

```bash
php artisan mail:send-donor-verification you@domain.com --approve=1
```

This queues `DonorVerificationStatus` using the first donor record in the database. Make sure there is at least one donor in the DB.

6) Troubleshooting
- If no email arrives, inspect `storage/logs/laravel.log` and `storage/logs/queue.log` for errors.
- Check `php artisan queue:failed` for failed jobs.
- If Gmail blocks connections, ensure the App Password is correct and 2FA is enabled.
- For production use, consider a transactional email provider (Mailgun/SendGrid/SES).

If you want, I can add a small admin UI button to re-send the notification and a background service example for Windows (nssm) or Linux (systemd).

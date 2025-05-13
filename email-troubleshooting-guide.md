# Email Notifications Troubleshooting Guide

## Quick Fix Checklist

If email notifications for new products are not being sent, follow these steps:

1. **Check Laravel Logs**
   ```
   tail -f storage/logs/laravel.log
   ```
   Look for error messages related to mail or notifications.

2. **Verify Queue Configuration**
   - Make sure `QUEUE_CONNECTION=database` is set in your `.env` file
   - Run migrations to create queue tables: 
     ```
     php artisan migrate
     ```
   - Start the queue worker:
     ```
     php artisan queue:work
     ```

3. **Test Mail Configuration**
   ```
   php artisan tinker
   Mail::raw('Test email', function($message) { $message->to('your-email@example.com')->subject('Test Email'); });
   ```

## Detailed Troubleshooting Steps

### 1. Email Configuration

Make sure your `.env` file has the correct mail configuration:

```
MAIL_MAILER=smtp
MAIL_HOST=your-mail-server.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=gudangku@example.com
MAIL_FROM_NAME="Gudangku Notification"
```

For testing, you can use the `log` driver instead:

```
MAIL_MAILER=log
```

This will write emails to the Laravel log file instead of sending them.

### 2. Queue Configuration

Email notifications use Laravel's queue system. Make sure it's configured properly:

1. **Set up database queue**:
   ```
   QUEUE_CONNECTION=database
   ```

2. **Create queue tables**:
   ```
   php artisan queue:table
   php artisan queue:failed-table
   php artisan migrate
   ```

3. **Start the queue worker**:
   ```
   php artisan queue:work
   ```
   
   In production, you should use a process manager like Supervisor to keep the queue worker running.

### 3. User Email Addresses

1. **Check if users have email addresses**:
   ```
   php artisan tinker
   User::where('umkm_id', 1)->get(['user_id', 'email']);
   ```

2. **Verify notification settings**:
   Make sure the User model has the `Notifiable` trait.

### 4. Common Issues and Solutions

#### Emails being queued but not sent

**Issue**: Emails are in the queue table but not being processed.
**Solution**: Run the queue worker with `php artisan queue:work`

#### "Unable to connect to SMTP server" error

**Issue**: SMTP server connection problems
**Solutions**:
- Verify SMTP credentials in `.env`
- Check if your server allows outgoing connections on the SMTP port
- Try a different SMTP provider

#### SSL Certificate Issues

**Issue**: SSL certificate verification failures
**Solutions**:
- Update your CA certificates
- Try disabling SSL verification (not recommended for production)

#### Timeouts

**Issue**: Connection or execution timeouts
**Solution**: Increase timeout settings in your mail configuration

### 5. Testing Email Manually

You can test sending emails directly using Tinker:

```php
php artisan tinker

// Import necessary classes
use App\Models\Barang;
use App\Models\User;
use App\Notifications\NewBarangNotification;

// Get a user and a product
$user = User::find(1);
$barang = Barang::find(1);

// Send the notification directly
$user->notify(new NewBarangNotification($barang, $user));
```

### 6. Advanced Debugging

1. **Enable mail debug mode**:
   ```
   MAIL_LOG_CHANNEL=daily
   ```

2. **Check mail server logs** if you have access.

3. **Inspect the queue**:
   ```
   php artisan queue:monitor
   php artisan queue:failed
   ```

### Need More Help?

If none of these solutions work, please:

1. Collect the full error logs
2. Note your environment configuration (PHP version, Laravel version)
3. Contact your system administrator or open an issue on our support channel 
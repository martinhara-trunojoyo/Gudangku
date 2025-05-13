# Email Notifications for Gudangku

This document explains how to configure email notifications for the Gudangku application. When a new product (barang) is added to the inventory, the system will automatically send an email notification to all admin users belonging to the same UMKM.

## Features Implemented

1. **NewBarangNotification**: A notification class that formats and sends emails when a new product is added
2. **Email to Admins**: Notification is sent to all admin users within the same UMKM
3. **Product Details**: The email includes details about the newly added product, including name, stock, and location
4. **Error Handling**: If the email fails to send, the application will log the error but still complete the product creation

## Configuration Steps

To ensure email notifications work correctly, please configure your email settings in the `.env` file:

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

### Options for Mail Testing

1. **Mailtrap**: For development, you can use Mailtrap to test emails without sending them to real users. Sign up at [mailtrap.io](https://mailtrap.io) and use their SMTP settings.

2. **Log Driver**: For simple testing, you can set `MAIL_MAILER=log` which will write emails to the Laravel log file.

3. **Gmail**: You can use Gmail for sending emails, but you'll need to enable "Less secure apps" in your Google account or use an App Password.

## Troubleshooting

If emails are not being sent:

1. Check the Laravel log file at `storage/logs/laravel.log` for errors
2. Verify that your SMTP server credentials are correct
3. Make sure the admin users have valid email addresses in the database
4. For queue processing, ensure your queue worker is running with `php artisan queue:work`

## Additional Information

- The notification is set up to use queues (implements ShouldQueue), so make sure to run a queue worker in production for better performance
- If you want to modify the email template, you can edit the `toMail` method in `app/Notifications/NewBarangNotification.php`
- To test the email functionality manually, you can use `php artisan tinker` and send a notification directly 
# Email Configuration Guide for MaasISO Contact Form

## Hosting Email Configuration Options

### Option 1: SMTP Configuration
If your hosting doesn't support PHP's built-in mail() function, consider:
1. Using PHPMailer library
2. Configuring SMTP settings with your hosting provider's email service
3. Using credentials from your hosting email (info@maasiso.nl)

### Option 2: Third-Party Email Services
- SendGrid
- Mailgun
- Amazon SES

### Troubleshooting Checklist
- Verify PHP mail() function is enabled
- Check hosting email server settings
- Ensure proper SPF and DKIM records for domain
- Test email sending through hosting control panel

### Recommended Next Steps
1. Contact hosting support (Clou86)
2. Ask about:
   - PHP mail() function configuration
   - SMTP settings
   - Recommended email sending method

### Potential Solutions
```php
// Example PHPMailer configuration
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'your.smtp.host';
$mail->SMTPAuth = true;
$mail->Username = 'your_username';
$mail->Password = 'your_password';
```

## Logging and Debugging
- Check contact-form-errors.log for detailed error information
- Review server error logs
- Enable PHP error reporting during troubleshooting
